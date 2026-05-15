<?php
namespace Tender\Handlers;

use Bitrix\Crm\Item;
use Bitrix\Crm\Service\Container;
use Bitrix\Main\Event;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Diag\Debug;
use Tender\Config;

class LogHandler
{
    protected static bool $isProcessing = false;

    public static function onAfterUpdate($event)
    {
        if (self::$isProcessing) {
	        return;
        }
        
        if (!Loader::includeModule('crm')) {
	        return;
        }

        $item = null;
        $debugInfo = "Method: onAfterUpdate";

        // 1. Получаем объект Item
        if ($event instanceof Event) {
            $item = $event->getParameter('item');
            $debugInfo .= " | Context: Event";
        } elseif (is_numeric($event)) {
            $factory = Container::getInstance()->getFactory(Config::SMART_PROCESS_ID);
            $item = $factory ? $factory->getItem((int)$event) : null;
            $debugInfo .= " | Context: ID (" . $event . ")";
        }

        // Вместо вывода всего объекта $item, пишем только факт его получения
        if (!$item instanceof Item) {
            Debug::writeToFile($debugInfo . " | Error: Item not found", "AI_TRACE", "tender_rest.log");
            return;
        }

        Debug::writeToFile($debugInfo . " | Success: Item " . $item->getId(), "AI_TRACE", "tender_rest.log");

        self::$isProcessing = true;
        self::processAiLogic($item);
        self::$isProcessing = false;
    }

    private static function processAiLogic($item)
    {
        $id = $item->getId();
        
        // Получаем значение поля
        $archiveUrlRaw = $item->get(Config::FIELD_ARCHIVE_URL); 
        $archiveUrl = '';

        if (is_array($archiveUrlRaw)) {
            $archiveUrl = (string)reset($archiveUrlRaw); 
        } else {
            $archiveUrl = (string)$archiveUrlRaw;
        }

        if (empty($archiveUrl)) {
            Debug::writeToFile("ID $id: Archive URL is empty", "SKIP", "tender_rest.log");
            return; 
        }

        $payload = [
            'tender_id'    => (string)$id,
            'archive_url'  => $archiveUrl,
            'title'        => (string)$item->getTitle(),
            'base_url'     => Config::getHostUrl(),
        ];

        // Логируем только полезную нагрузку (массив строк), это безопасно
        Debug::writeToFile($payload, "SENDING_TO_AI", "tender_rest.log");

        self::sendToAi($payload);
    }

    private static function sendToAi($data)
    {
        $httpClient = new HttpClient(['socketTimeout' => 10, 'streamTimeout' => 10]);
        $httpClient->setHeader('Content-Type', 'application/json');
        
        $body = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response = $httpClient->post(Config::AI_SERVER_URL, $body);

        Debug::writeToFile(
            [
                'STATUS' => $httpClient->getStatus(),
                'REPLY' => json_decode($response, true) ?: $response
            ],
            "AI_RESPONSE", 
            "tender_rest.log"
        );
    }

    public static function update($data)
    {
        $itemId = (int)$data['tender_id'];
        if ($itemId <= 0 || !Loader::includeModule('crm')) return;

        $factory = Container::getInstance()->getFactory(Config::SMART_PROCESS_ID);
        $item = $factory ? $factory->getItem($itemId) : null;

        if ($item) {
            // Чтобы не зациклиться, если этот метод вызовется из события
            self::$isProcessing = true; 

            // 1. ЗАПИСЬ ТЕКСТОВЫХ ДАННЫХ
            $item->set(Config::FIELD_SUMMARY, $data['summary_text'] ?? '');
            
            $currentTitle = $item->get('TITLE');
            if (strpos($currentTitle, '[AI READY]') === false) {
                $item->set('TITLE', '[AI READY] ' . $currentTitle);
            }

            // 2. !!! ВОЗВРАЩАЕМ ОПЕРАЦИЮ С ФАЙЛОМ !!!
            // Если в ответе от ИИ пришла таблица и у нас есть disk_file_id
            if (!empty($data['disk_file_id'])) {
                // Используем проверенный префикс 'n' + ID объекта Диска
                $item->set(Config::FIELD_TABLE_TENDER, 'n' . $data['disk_file_id']);
            }

            // 3. СОХРАНЕНИЕ
            $operation = $factory->getUpdateOperation($item);
            $operation->disableCheckAccess(); // Чтобы права не блокировали запись
            $res = $operation->launch();

            // 4. ПИНГ ТРИГГЕРА (через ваш проверенный HttpClient)
            $entityId = Config::SMART_PROCESS_ID;
            $triggerUrl = "https://tk.tandem-consult.ru/rest/608/b9643sh3r8z1qfs8/crm.automation.trigger/?target=DYNAMIC_{$entityId}_{$itemId}&code=6py38";
            $http = new HttpClient();
            $http->get($triggerUrl);

            // 5. ЛОГИРОВАНИЕ
            Debug::writeToFile(
                "ID: $itemId | Update: " . ($res->isSuccess() ? 'OK' : 'FAIL') . " | File: " . ($data['disk_file_id'] ?? 'none'), 
                "AI_CALLBACK_HANDLER", 
                "tender_rest.log"
            );

            self::$isProcessing = false;
        }
    }

}
