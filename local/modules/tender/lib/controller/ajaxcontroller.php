<?php
namespace Tender\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Crm\Service;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell;
use Tender\Config;

class AjaxController extends Controller
{
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
            Loader::includeModule('tender');

            // Передаем ID в Handler. Теперь он найдет Item 3057 и отправит в ИИ.
            \Tender\Handlers\LogHandler::onAfterUpdate($itemId);

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

            if (!$data || !isset($data['tender_id'])) {
                return ['status' => 'error', 'message' => 'No ID found'];
            }

            $tenderId = (int)$data['tender_id'];
            $isCompleted = (($data['status'] ?? '') === 'completed');

            // 1. АВТОРИЗАЦИЯ
            if (!is_object($USER)) { $USER = new \CUser; }
            $USER->Authorize($authorizedUserId);

            if (!\Bitrix\Main\Loader::includeModule('crm') || !\Bitrix\Main\Loader::includeModule('disk')) {
                return ['status' => 'error', 'message' => 'Modules missing'];
            }

            $entityTypeId = (int)\Tender\Config::SMART_PROCESS_ID;
            $container = \Bitrix\Crm\Service\Container::getInstance();
            $factory = $container->getFactory($entityTypeId);
            
            if (!$factory) return ['status' => 'error', 'message' => "Factory not found"];

            $item = $factory->getItem($tenderId);
            if (!$item) return ['status' => 'error', 'message' => 'Item not found'];

            // 2. ЗАПОЛНЕНИЕ ТЕКСТОВЫХ ПОЛЕЙ
            $item->set(\Tender\Config::FIELD_SUMMARY, $data['summary_text'] ?? '');
            $item->set(\Tender\Config::FIELD_ERROR, $isCompleted ? "" : ($data['error_message'] ?? ''));

            // 3. СОХРАНЕНИЕ ТЕКСТА
            $operation = $factory->getUpdateOperation($item);
            $operation->disableCheckAccess();
            $result = $operation->launch();

            // 4. ОБРАБОТКА ФАЙЛА (СИЛОВОЙ МЕТОД SQL)
            if (!empty($data['table_tender'])) {
                require_once($_SERVER["DOCUMENT_ROOT"] . "/local/vendor/autoload.php");
                $diskFileId = $this->generateExcelAndSaveToDisk($data['table_tender'], $tenderId);
                
                if ($diskFileId) {
                    $diskFile = \Bitrix\Disk\File::loadById($diskFileId);
                    if ($diskFile) {
                        $bFileId = (int)$diskFile->getFileId();
                        $fieldName = \Tender\Config::FIELD_TABLE_TENDER;
                        
                        // Прямая запись в БД, так как API Smart Process блокирует файлы
                        $connection = \Bitrix\Main\Application::getConnection();
                        $tableName = "b_crm_dynamic_items_" . $entityTypeId;
                        $sql = "UPDATE {$tableName} SET {$fieldName} = '{$bFileId}' WHERE ID = {$tenderId}";
                        $connection->queryExecute($sql);
                        
                        // Очистка кеша для визуального обновления
                        \Bitrix\Main\Application::getInstance()->getTaggedCache()->clearByTag("crm_dynamic_item_" . $tenderId);
                    }
                }
            }

            $USER->Logout();

            if ($result->isSuccess()) {
                if ($isCompleted) { $this->fireAutomationTrigger($tenderId); }
                return ['status' => 'success'];
            }

            return ['status' => 'error', 'errors' => $result->getErrorMessages()];

        } catch (\Throwable $e) {
            if (is_object($USER)) { $USER->Logout(); }
            \Bitrix\Main\Diag\Debug::writeToFile($e->getMessage(), "CALLBACK_FATAL", "tender_error.log");
            return ['status' => 'error', 'exception' => $e->getMessage()];
        }
    }


    /**
     * Генерация XLSX и сохранение на Диск
     */
    private function generateExcelAndSaveToDisk(array $tableData, $tenderId)
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

            $storage = \Bitrix\Disk\Driver::getInstance()->getStorageByCommonId('shared_files_s1') 
                       ?? \Bitrix\Disk\Driver::getInstance()->getStorageByUserId(1);
            
            if (!$storage) return null;

            $fileArray = \CFile::MakeFileArray($tmpPath);
            $fileArray['name'] = $fileName;

            $newFile = $storage->getRootObject()->uploadFile($fileArray, ['CREATED_BY' => 1]);
            unlink($tmpPath);

            return $newFile ? $newFile->getId() : null;
        } catch (\Exception $e) {
            Debug::writeToFile($e->getMessage(), "EXCEL_ERR", "tender_error.log");
            return null;
        }
    }

    /**
     * Сохранение готового Base64 (если файл приходит строкой)
     */
    private function saveBase64ToDisk($base64String, $tenderId)
    {
        try {
            $fileContent = base64_decode($base64String);
            $tmpName = tempnam(sys_get_temp_dir(), 'tender_');
            file_put_contents($tmpName, $fileContent);

            $storage = \Bitrix\Disk\Driver::getInstance()->getStorageByCommonId('shared_files_s1')
                       ?? \Bitrix\Disk\Driver::getInstance()->getStorageByUserId(1);

            $arFile = \CFile::MakeFileArray($tmpName);
            $arFile['name'] = "tender_result_{$tenderId}.xlsx";

            $newFile = $storage->getRootObject()->uploadFile($arFile, ['CREATED_BY' => 1]);
            unlink($tmpName);

            return $newFile ? $newFile->getId() : null;
        } catch (\Exception $e) {
            Debug::writeToFile($e->getMessage(), "B64_ERR", "tender_error.log");
            return null;
        }
    }

    /**
     * Активация триггера робота
     */
        /**
     * Активация триггера робота через проверенный HttpClient (Webhook)
     */
    private function fireAutomationTrigger($id)
    {
        // Берем ID сущности из конфига (например, 1186 или 1354)
        $entityId = \Tender\Config::SMART_PROCESS_ID;
        $triggerCode = "6py38";
        
        // Формируем проверенный URL
        $triggerUrl = "https://tk.tandem-consult.ru/rest/608/b9643sh3r8z1qfs8/crm.automation.trigger/?" . http_build_query([
            'target' => "DYNAMIC_{$entityId}_{$id}",
            'code' => $triggerCode
        ]);

        // Асинхронный вызов через HttpClient
        $httpClient = new \Bitrix\Main\Web\HttpClient();
        $httpClient->setTimeout(5);
        $httpClient->get($triggerUrl);
        
        // Логируем для уверенности, что ссылка сформирована верно
        \Bitrix\Main\Diag\Debug::writeToFile("Trigger fired: " . $triggerUrl, "TRIGGER_DEBUG", "tender_rest.log");
    }


}
