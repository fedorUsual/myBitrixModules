<?php
namespace Faleev\Tender\Controller;

use Bitrix\Crm\Service\Container;
use Bitrix\Main\Type\Date;
use Bitrix\Disk\Driver;
use Bitrix\Disk\File;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Web\HttpClient;
use Exception;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell;
use Faleev\Tender\Config;
use Faleev\Tender\Handlers\LogHandler;
use Faleev\Tender\Service\EmailTenderPipeline;
use Throwable;

class AjaxController extends Controller
{
	protected static string $moduleId = 'faleev.tender';

    /**
     * Конфигурация фильтров для экшенов
     */
    public function configureActions()
    {
        return [
            'sync' => [
                '-prefilters' => [
                    ActionFilter\Csrf::class,
                    ActionFilter\Authentication::class,
                ],
            ],
            'callback' => [
                '-prefilters' => [
                    ActionFilter\Csrf::class,
                    ActionFilter\Authentication::class,
                ],
            ],
            'render' => [
                '-prefilters' => [
                    ActionFilter\Csrf::class,
                    ActionFilter\Authentication::class,
                ],
            ],
            'createCard' => [
                '-prefilters' => [
                    ActionFilter\Csrf::class,
                    ActionFilter\Authentication::class,
                ],
            ],
            'callbackEmail' => [
                '-prefilters' => [
                    ActionFilter\Csrf::class,
                    ActionFilter\Authentication::class,
                ],
            ],
        ];
    }
	/** todo:: удалить полностью, если все будет отрабатывать нормально без вебхука*/
/*
    public function syncAction()
    {
        $request = $this->getRequest();
        $uri = $request->getRequestUri();

        // Парсим document_id из строки запроса вручную
        parse_str(parse_url($uri, PHP_URL_QUERY), $queryParams);
        $itemId = isset($queryParams['document_id']) ? (int)$queryParams['document_id'] : 0;

        Debug::writeToFile(
            "SYNC_START | URI: {$uri} | document_id: {$itemId}",
            "TENDER_DEBUG",
            "tender_rest.log"
        );

        if ($itemId <= 0) {
            $postData = $request->getPostList()->toArray();
            $itemId = (int)($postData['data']['FIELDS']['ID'] ?? $postData['document_id'] ?? 0);
        }

        // Логируем для отладки
        Debug::writeToFile(
            "SYNC_START | ID: " . $itemId,
            "TENDER_DEBUG",
            "tender_rest.log"
        );

        if ($itemId > 0) {
            Loader::includeModule('crm');
            Loader::includeModule('faleev.tender');

            // Передаем ID в Handler. Теперь он найдет Item 3057 и отправит в ИИ.
            LogHandler::onAfterUpdate($itemId);

            return ['status' => 'success', 'id' => $itemId];
        }

        return ['status' => 'error', 'message' => 'No valid ID found'];
    }*/

    /**
     * Основной метод обработки колбэка от нейросети/внешнего сервиса
     */
    public function callbackAction()
    {
        global $USER;
        $authorizedUserId = 563;

        try {
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
			$options = Option::getForModule(self::$moduleId);
            if (!$data || !isset($data['tender_id'])) {
                return ['status' => 'error', 'message' => 'No ID found'];
            }

            $tenderId = (int)$data['tender_id'];
            $isCompleted = (($data['status'] ?? '') === 'completed');

            // 1. АВТОРИЗАЦИЯ
            if (!is_object($USER)) {
				$USER = new \CUser;
			}
            $USER->Authorize($authorizedUserId);

            if (!Loader::includeModule('crm') || !Loader::includeModule('disk')) {
                return ['status' => 'error', 'message' => 'Modules missing'];
            }

            $entityTypeId = (int)$options['smart_process_id'];
            $container = Container::getInstance();
            $factory = $container->getFactory($entityTypeId);

            if (!$factory) {
	            return ['status' => 'error', 'message' => "Factory not found"];
            }

            $item = $factory->getItem($tenderId);
            if (!$item) {
	            return ['status' => 'error', 'message' => 'Item not found'];
            }

            // 2. ЗАПОЛНЕНИЕ ТЕКСТОВЫХ ПОЛЕЙ
            $item->set($options['field_summary'], $data['summary_text'] ?? '');
            $item->set($options['field_error'], $isCompleted ? "" : ($data['error_message'] ?? ''));
	        // 3. ОБРАБОТКА ФАЙЛА
			if (!empty($data['table_tender'])) {
				require_once($_SERVER["DOCUMENT_ROOT"] . "/local/vendor/autoload.php");
				$diskFileId = $this->generateExcelAndSaveToDisk($data['table_tender'], $tenderId);
				if ($diskFileId) {
					$diskFile = File::loadById($diskFileId);
					if ($diskFile) {
						$bFileId = (int)$diskFile->getFileId();
						$bFile = \CFile::MakeFileArray($bFileId);
						$fieldName = $options['field_table_tender'];

						$fileField = $factory->getFieldsCollection()->getField($fieldName);
						$fileUploader = $container->getFileUploader();
						$checkResult = $fileUploader->checkFile($fileField, $bFile);
						if (!$checkResult->isSuccess())
						{
							throw new Exception(implode(',' . PHP_EOL, $checkResult->getErrorMessages()));
						}

						$savedFileId = $fileUploader->saveFilePersistently($fileField, $bFile);

						$item->set($fieldName, $savedFileId);
					}
				}
			}

            // 4. СОХРАНЕНИЕ ТЕКСТА
            $operation = $factory->getUpdateOperation($item);
            $operation->disableCheckAccess();
            $result = $operation->launch();

			/** todo:: раскомментировать, в случае, если правильный способ не заработает */
            // 4. ОБРАБОТКА ФАЙЛА (СИЛОВОЙ МЕТОД SQL)
            /*if (!empty($data['table_tender'])) {
                require_once($_SERVER["DOCUMENT_ROOT"] . "/local/vendor/autoload.php");
                $diskFileId = $this->generateExcelAndSaveToDisk($data['table_tender'], $tenderId);

                if ($diskFileId) {
                    $diskFile = File::loadById($diskFileId);
                    if ($diskFile) {
                        $bFileId = (int)$diskFile->getFileId();
                        $fieldName = $options['field_table_tender'];

                        // Прямая запись в БД, так как API Smart Process блокирует файлы
                        $connection = Application::getConnection();
                        $tableName = "b_crm_dynamic_items_" . $entityTypeId;
                        $sql = "UPDATE {$tableName} SET {$fieldName} = '{$bFileId}' WHERE ID = {$tenderId}";
                        $connection->queryExecute($sql);

                        // Очистка кеша для визуального обновления
                        Application::getInstance()->getTaggedCache()->clearByTag("crm_dynamic_item_" . $tenderId);
                    }
                }
            }*/

            $USER->Logout();

            if ($result->isSuccess()) {
                if ($isCompleted) { $this->fireAutomationTrigger($tenderId); }
                return ['status' => 'success'];
            }

            return ['status' => 'error', 'errors' => $result->getErrorMessages()];

        } catch (Throwable $e) {
            if (is_object($USER)) { $USER->Logout(); }
            Debug::writeToFile($e->getMessage(), "CALLBACK_FATAL", "tender_error.log");
            return ['status' => 'error', 'exception' => $e->getMessage()];
        }
    }

    /**
     * Создаёт карточку смарт-процесса "Заявки ИТР" из структурированного ответа ИИ.
     *
     * Endpoint accepts JSON or multipart/form-data. Uploaded files from "documents[]"
     * are saved through CRM file uploader when FIELD_DOCUMENTS is configured.
     */
    public function createCardAction(): array
    {
        global $USER;

        $authorizedUserId = (int)Option::get(self::$moduleId, 'DEFAULT_RESPONSIBLE_ID', 563);

        try {
            if (!is_object($USER)) {
                $USER = new \CUser;
            }
            $USER->Authorize($authorizedUserId);

            if (!Loader::includeModule('crm')) {
                return ['status' => 'error', 'message' => 'CRM module is not installed'];
            }

            return $this->createCardFromPayload($this->readRenderPayload($this->getRequest()));
        } catch (Throwable $e) {
            Debug::writeToFile($e->getMessage(), 'RENDER_FATAL', 'tender_error.log');
            return ['status' => 'error', 'exception' => $e->getMessage()];
        } finally {
            if (is_object($USER)) {
                $USER->Logout();
            }
        }
    }

    public function callbackEmailAction(): array
    {
        try {
            $options = Option::getForModule(self::$moduleId);
            //Debug::writeToFile('Stage-1 callbackEmailAction', 'CALLBACK_EMAIL_TEST', 'tender_error.log');
            $pipeline = new EmailTenderPipeline($options);
            //Debug::writeToFile('Stage-2 callbackEmailAction', 'CALLBACK_EMAIL_TEST', 'tender_error.log');
            return $pipeline->run(function (array $payload, array $attachments): array {
                return $this->createCardFromEmailPayload($payload, $attachments);
            });
        } catch (Throwable $e) {
            Debug::writeToFile($e->getMessage(), 'CALLBACK_EMAIL_FATAL', 'tender_error.log');
            return ['status' => 'error', 'exception' => $e->getMessage()];
        }
    }


	/**
	 * Генерация XLSX и сохранение на Диск
	 * @param array $tableData
	 * @param int|string $tenderId
	 * @return int|null
	 */
    private function generateExcelAndSaveToDisk(array $tableData, int|string $tenderId): ?int
    {
        try {
            $fileName = "tender_result_{$tenderId}_" . date('His') . ".xlsx";
            $tmpPath = tempnam(sys_get_temp_dir(), 'xlsx');

            $writer = new Writer();
            $writer->openToFile($tmpPath);

            // Шапка
            $firstRow = reset($tableData);
            if (is_array($firstRow)) {
                $writer->addRow(Row::fromValues(array_keys($firstRow)));
            }

            // Данные
            foreach ($tableData as $dataRow) {
                $writer->addRow(Row::fromValues(array_values($dataRow)));
            }
            $writer->close();

            $storage = Driver::getInstance()->getStorageByCommonId('shared_files_s1')
                       ?? Driver::getInstance()->getStorageByUserId(1);

            if (!$storage) return null;

            $fileArray = \CFile::MakeFileArray($tmpPath);
            $fileArray['name'] = $fileName;

            $newFile = $storage->getRootObject()->uploadFile($fileArray, ['CREATED_BY' => 1]);
            unlink($tmpPath);

            return $newFile ? $newFile->getId() : null;
        } catch (Exception $e) {
            Debug::writeToFile($e->getMessage(), "EXCEL_ERR", "tender_error.log");
            return null;
        }
    }

	/**
	 * Сохранение готового Base64 (если файл приходит строкой)
	 * @param string $base64String
	 * @param int|string $tenderId
	 * @return int|null
	 */
    private function saveBase64ToDisk(string $base64String, int|string $tenderId): ?int
    {
        try {
            $fileContent = base64_decode($base64String);
            $tmpName = tempnam(sys_get_temp_dir(), 'tender_');
            file_put_contents($tmpName, $fileContent);

            $storage = Driver::getInstance()->getStorageByCommonId('shared_files_s1')
                       ?? Driver::getInstance()->getStorageByUserId(1);

            $arFile = \CFile::MakeFileArray($tmpName);
            $arFile['name'] = "tender_result_{$tenderId}.xlsx";

            $newFile = $storage->getRootObject()->uploadFile($arFile, ['CREATED_BY' => 1]);
            unlink($tmpName);

            return $newFile ? $newFile->getId() : null;
        } catch (Exception $e) {
            Debug::writeToFile($e->getMessage(), "B64_ERR", "tender_error.log");
            return null;
        }
    }

    private function readRenderPayload($request): array
    {
        $rawInput = file_get_contents('php://input');
        $contentType = (string)$request->getHeader('Content-Type');
        $payload = [];

        if (stripos($contentType, 'application/json') !== false && $rawInput !== '') {
            $payload = json_decode($rawInput, true) ?: [];
        } else {
            $payload = $request->getPostList()->toArray();
            if (isset($payload['payload']) && is_string($payload['payload'])) {
                $decoded = json_decode($payload['payload'], true);
                if (is_array($decoded)) {
                    $payload = $decoded;
                }
            }
        }

        return is_array($payload) ? $payload : [];
    }

    private function createCardFromEmailPayload(array $payload, array $attachments): array
    {
        $_FILES['documents'] = $this->attachmentsToFiles($attachments);
        $_POST['payload'] = json_encode($payload, JSON_UNESCAPED_UNICODE);

        try {
            return $this->createCardFromPayload($payload);
        } finally {
            foreach ($this->normalizeUploadedFiles($_FILES['documents'] ?? []) as $file) {
                if (!empty($file['tmp_name']) && is_file($file['tmp_name'])) {
                    unlink($file['tmp_name']);
                }
            }
            unset($_FILES['documents'], $_POST['payload']);
        }
    }

    private function createCardFromPayload(array $data): array
    {
        global $USER;

        $authorizedUserId = (int)Option::get(self::$moduleId, 'DEFAULT_RESPONSIBLE_ID', 563);

        if (!is_object($USER)) {
            $USER = new \CUser;
        }
        $USER->Authorize($authorizedUserId);

        if (!Loader::includeModule('crm')) {
            return ['status' => 'error', 'message' => 'CRM module is not installed'];
        }

        $validationErrors = $this->validateRenderPayload($data);
        if ($validationErrors) {
            return ['status' => 'error', 'errors' => $validationErrors];
        }

        $options = Option::getForModule(self::$moduleId);
        $entityTypeId = (int)($options['SMART_PROCESS_ID'] ?? $options['smart_process_id'] ?? Config::SMART_PROCESS_ID);
        $container = Container::getInstance();
        $factory = $container->getFactory($entityTypeId);
        if (!$factory) {
            return ['status' => 'error', 'message' => "Factory not found for entityTypeId={$entityTypeId}"];
        }

        $initialData = [
            'TITLE' => $data['description'],
            'ASSIGNED_BY_ID' => $authorizedUserId,
            'CREATED_BY' => $authorizedUserId,
            'UPDATED_BY' => $authorizedUserId,
        ];

        $categoryId = (int)($options['DEFAULT_CATEGORY_ID'] ?? 65);
        if ($categoryId > 0) {
            $initialData['CATEGORY_ID'] = $categoryId;
        }

        $stageId = (string)($options['DEFAULT_STAGE_ID'] ?? '');
        if ($stageId !== '') {
            $initialData['STAGE_ID'] = $stageId;
        }

        $item = $factory->createItem($initialData);
        $fieldMap = $this->getRenderFieldMap($options);
        $this->setMappedField($item, $fieldMap, 'request_type', $data['request_type']);
        $this->setMappedField($item, $fieldMap, 'activity_direction', $data['activity_direction']);
        $this->setMappedField($item, $fieldMap, 'description', $data['description']);
        $this->setMappedField($item, $fieldMap, 'end_user_inn', $data['end_user']['inn'] ?? '');
        $this->setMappedField($item, $fieldMap, 'end_user_name', $data['end_user']['name'] ?? '');
        $this->setMappedField($item, $fieldMap, 'source', $data['source']);
        $this->setMappedField($item, $fieldMap, 'lot_number', $data['lot_number']);
        $this->setMappedField($item, $fieldMap, 'tender_files_url', $data['tender_files_url']);
        $this->setMappedField($item, $fieldMap, 'scoring', $this->formatScoring($data['scoring']));

        if (!empty($fieldMap['tkp_deadline'])) {
            $item->set($fieldMap['tkp_deadline'], new Date($data['tkp_deadline'], 'Y-m-d'));
        }

        $documentField = $fieldMap['documents'] ?? '';
        if ($documentField !== '' && !empty($_FILES['documents'])) {
            $item->set($documentField, $this->prepareUploadedFiles($documentField, $_FILES['documents'], $factory, $container));
        }

        $operation = $factory->getAddOperation($item);
        $operation->disableCheckAccess();
        $result = $operation->launch();

        if (!$result->isSuccess()) {
            return ['status' => 'error', 'errors' => $result->getErrorMessages()];
        }

        return [
            'status' => 'success',
            'id' => $item->getId(),
            'entityTypeId' => $entityTypeId,
        ];
    }

    private function attachmentsToFiles(array $attachments): array
    {
        $files = [
            'name' => [],
            'type' => [],
            'tmp_name' => [],
            'error' => [],
            'size' => [],
        ];

        foreach ($attachments as $attachment) {
            $tmp = tempnam(sys_get_temp_dir(), 'tender_doc_');
            file_put_contents($tmp, (string)($attachment['data'] ?? ''));
            $files['name'][] = (string)($attachment['filename'] ?? 'document');
            $files['type'][] = 'application/octet-stream';
            $files['tmp_name'][] = $tmp;
            $files['error'][] = UPLOAD_ERR_OK;
            $files['size'][] = filesize($tmp) ?: 0;
        }

        return $files;
    }

    private function validateRenderPayload(array $data): array
    {
        $errors = [];
        $required = [
            'request_type',
            'activity_direction',
            'description',
            'source',
            'lot_number',
            'tkp_deadline',
            'tender_files_url',
            'scoring',
        ];

        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $errors[] = "Field {$field} is required";
            }
        }

        if (!in_array($data['request_type'] ?? null, ['contest', 'survey'], true)) {
            $errors[] = 'Field request_type must be contest or survey';
        }
        if (!in_array($data['activity_direction'] ?? null, ['SP', 'S'], true)) {
            $errors[] = 'Field activity_direction must be SP or S';
        }
        if (($data['source'] ?? null) !== 'email') {
            $errors[] = 'Field source must be email';
        }
        if (empty($data['end_user']) || !is_array($data['end_user'])) {
            $errors[] = 'Field end_user is required';
        } elseif (empty($data['end_user']['inn']) && empty($data['end_user']['name'])) {
            $errors[] = 'At least one of end_user.inn or end_user.name is required';
        }
        if (!empty($data['tkp_deadline']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$data['tkp_deadline'])) {
            $errors[] = 'Field tkp_deadline must use YYYY-MM-DD format';
        }

        return $errors;
    }

    private function getRenderFieldMap(array $options): array
    {
        return [
            'request_type' => (string)($options['FIELD_REQUEST_TYPE'] ?? 'UF_CRM_57_RUROBOT_TORGI_TYPE_ZAKUPKI'),
            'activity_direction' => (string)($options['FIELD_ACTIVITY_DIRECTION'] ?? 'UF_CRM_57_1752592467'),
            'description' => (string)($options['FIELD_DESCRIPTION'] ?? 'UF_CRM_57_RUROBOT_TORGI_DESCR'),
            'end_user_inn' => (string)($options['FIELD_END_USER_INN'] ?? 'UF_CRM_57_RUROBOT_TORGI_INN_ZAKAZCHIKA'),
            'end_user_name' => (string)($options['FIELD_END_USER_NAME'] ?? 'UF_CRM_57_RUROBOT_TORGI_ZAKAZCHIK'),
            'source' => (string)($options['FIELD_SOURCE'] ?? 'SOURCE_ID'),
            'lot_number' => (string)($options['FIELD_LOT_NUMBER'] ?? 'UF_CRM_57_RUROBOT_TORGI_NUMBER_ZAKUPKI'),
            'tkp_deadline' => (string)($options['FIELD_TKP_DEADLINE'] ?? 'UF_CRM_57_RUROBOT_TORGI_STOP_DATE'),
            'tender_files_url' => (string)($options['FIELD_TENDER_FILES_URL'] ?? 'UF_CRM_57_RUROBOT_TORGI_FILE_URL'),
            'scoring' => (string)($options['FIELD_SCORING'] ?? 'UF_CRM_57_1757054330'),
            'documents' => (string)($options['FIELD_DOCUMENTS'] ?? 'UF_CRM_57_RUROBOT_TORGI_FILE'),
        ];
    }

    private function setMappedField($item, array $fieldMap, string $key, mixed $value): void
    {
        $fieldName = $fieldMap[$key] ?? '';
        if ($fieldName === '' || $value === null || $value === '') {
            return;
        }

        $item->set($fieldName, $value);
    }

    private function formatScoring(array $scoring): string
    {
        $pros = array_filter((array)($scoring['pros'] ?? []));
        $cons = array_filter((array)($scoring['cons'] ?? []));

        $lines = [];
        if ($pros) {
            $lines[] = 'За:';
            foreach ($pros as $item) {
                $lines[] = '- ' . $item;
            }
        }
        if ($cons) {
            $lines[] = 'Против:';
            foreach ($cons as $item) {
                $lines[] = '- ' . $item;
            }
        }

        return implode(PHP_EOL, $lines);
    }

    private function prepareUploadedFiles(string $fieldName, array $files, $factory, Container $container): array
    {
        $prepared = [];
        $normalized = $this->normalizeUploadedFiles($files);
        $fileField = $factory->getFieldsCollection()->getField($fieldName);
        $fileUploader = $container->getFileUploader();

        foreach ($normalized as $file) {
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }

            $fileArray = \CFile::MakeFileArray($file['tmp_name']);
            $fileArray['name'] = $file['name'];
            $fileArray['type'] = $file['type'] ?? '';

            $checkResult = $fileUploader->checkFile($fileField, $fileArray);
            if (!$checkResult->isSuccess()) {
                throw new Exception(implode(',' . PHP_EOL, $checkResult->getErrorMessages()));
            }

            $prepared[] = $fileUploader->saveFilePersistently($fileField, $fileArray);
        }

        return $prepared;
    }

    private function normalizeUploadedFiles(array $files): array
    {
        if (!is_array($files['name'] ?? null)) {
            return [$files];
        }

        $normalized = [];
        foreach ($files['name'] as $index => $name) {
            $normalized[] = [
                'name' => $name,
                'type' => $files['type'][$index] ?? '',
                'tmp_name' => $files['tmp_name'][$index] ?? '',
                'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size' => $files['size'][$index] ?? 0,
            ];
        }

        return $normalized;
    }

	/**
	 * Активация триггера робота через проверенный HttpClient (Webhook)
	 * @param int|string $id
	 * @throws Exception
	 */
    private function fireAutomationTrigger(int|string $id): void
    {
        // Берем ID сущности из конфига (например, 1186 или 1354)
	    $options = Option::getForModule(self::$moduleId);
        $entityId = $options['smart_process_id'];
        $triggerCode = $options['trigger_code'] ?? "6py38";

        // Формируем проверенный URL
        $triggerUrl = Config::getHostUrl() . "/rest/608/b9643sh3r8z1qfs8/crm.automation.trigger/?" . http_build_query([
            'target' => "DYNAMIC_{$entityId}_{$id}",
            'code' => $triggerCode
        ]);

        // Асинхронный вызов через HttpClient
        $httpClient = new HttpClient();
        $httpClient->setTimeout(5);
        $httpClient->get($triggerUrl);

        // Логируем для уверенности, что ссылка сформирована верно
        Debug::writeToFile("Trigger fired: " . $triggerUrl, "TRIGGER_DEBUG", "tender_rest.log");
    }

}
