<?php
namespace Faleev\Tender\Controller;

use Bitrix\Crm\Service\Container;
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
        ];
    }

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
    }

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
