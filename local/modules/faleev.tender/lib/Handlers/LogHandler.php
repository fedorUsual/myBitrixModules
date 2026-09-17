<?php
namespace Faleev\Tender\Handlers;

use Bitrix\Crm\Item;
use Bitrix\Crm\Item\Dynamic;
use Bitrix\Crm\Service\Container;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Event;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\HttpClient;
use Exception;
use Faleev\Tender\Config;
use Throwable;

class LogHandler
{
	protected static string $module_id = "faleev.tender";
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
			$options = Option::getForModule(self::$module_id);
            $factory = Container::getInstance()->getFactory($options['smart_process_id']);
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
	    $options = Option::getForModule(self::$module_id);
        $archiveUrlRaw = $item->get($options['field_archive_url']);
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

	/**
	 * @throws Exception
	 */
	private static function sendToAi($data)
    {
        $httpClient = new HttpClient(['socketTimeout' => 10, 'streamTimeout' => 10]);
        $httpClient->setHeader('Content-Type', 'application/json');
        
        $body = json_encode($data, JSON_UNESCAPED_UNICODE);
	    $options = Option::getForModule(self::$module_id);
	    try {
		    $response = $httpClient->post($options['ai_server_url'], $body);
		    Debug::writeToFile(
			    [
				    'STATUS' => $httpClient->getStatus(),
				    'REPLY' => json_decode($response, true) ?: $response,
			    ],
			    "AI_RESPONSE",
			    "tender_rest.log"
		    );
	    } catch (Throwable $e) {
		    Debug::writeToFile(
			    [
				    'STATUS' => '500',
				    'REPLY' => $e->getMessage() . PHP_EOL . $e->getTraceAsString(),
			    ],
			    "AI_RESPONSE",
			    "tender_rest.log"
		    );
	    }
    }

    public static function update($data)
    {
        $itemId = (int)$data['tender_id'];
        if ($itemId <= 0 || !Loader::includeModule('crm')) {
	        return;
        }

	    $options = Option::getForModule(self::$module_id);
	    $factory = Container::getInstance()->getFactory($options['smart_process_id']);
        $item = $factory ? $factory->getItem($itemId) : null;

        if ($item) {
            // Чтобы не зациклиться, если этот метод вызовется из события
            self::$isProcessing = true; 

            // 1. ЗАПИСЬ ТЕКСТОВЫХ ДАННЫХ
            $item->set($options['field_summary'], $data['summary_text'] ?? '');
            
            $currentTitle = $item->get('TITLE');
            if (strpos($currentTitle, '[AI READY]') === false) {
                $item->set('TITLE', '[AI READY] ' . $currentTitle);
            }

            // 2. !!! ВОЗВРАЩАЕМ ОПЕРАЦИЮ С ФАЙЛОМ !!!
            // Если в ответе от ИИ пришла таблица и у нас есть disk_file_id
            if (!empty($data['disk_file_id'])) {
                // Используем проверенный префикс 'n' + ID объекта Диска
                $item->set($options['field_table_tender'], 'n' . $data['disk_file_id']);
            }

            // 3. СОХРАНЕНИЕ
            $operation = $factory->getUpdateOperation($item);
            $operation->disableCheckAccess(); // Чтобы права не блокировали запись
            $res = $operation->launch();

            // 4. ПИНГ ТРИГГЕРА (через ваш проверенный HttpClient)
//	        $entityId = $options['smart_process_id'];
//            $triggerUrl = Config::getHostUrl() . "/rest/608/b9643sh3r8z1qfs8/crm.automation.trigger/?target=DYNAMIC_{$entityId}_{$itemId}&code=6py38";
//            $http = new HttpClient();
//            $http->get($triggerUrl);

            // 5. ЛОГИРОВАНИЕ
            Debug::writeToFile(
                "ID: $itemId | Update: " . ($res->isSuccess() ? 'OK' : 'FAIL') . " | File: " . ($data['disk_file_id'] ?? 'none'), 
                "AI_CALLBACK_HANDLER", 
                "tender_rest.log"
            );

            self::$isProcessing = false;
        }
    }

	public static function onDynamicAdd(Event $event)
	{
		$moduleId = 'faleev.tender';
		Loader::includeModule($moduleId);
		/** @var Dynamic $item */
		$item = $event->getParameter('item');
		$itemData = $item->getData();
		$smartId = Option::get($moduleId, 'SMART_PROCESS_ID');
		$entityId = $item->getEntityTypeId();
		// смотрим нужный ли это смарт-процесс
		if ((int)$smartId !== $entityId) {
			return;
		}

		// получаем допустимые значения поля "Выполненные сценарии обработки ИИ"
		$statusAiField = Option::get($moduleId, 'AI_WORK_STATUS_FIELD');
		$statusAI = Option::get($moduleId, 'AI_WORK_STATUS');
		$arStatusesAI = explode(',', $statusAI);
		$arStatusesAI = array_map(function ($v) {
			return trim($v);
		}, $arStatusesAI);

		// получаем допустимую для отправки в ИИ стадию заявки
		$enableStage = Option::get($moduleId, 'ENABLE_STAGE');

		// получаем допустимые значения поля "Направление деятельности"
		$workDirectionField = Option::get($moduleId, 'WORK_DIRECTION_FIELD');
		$workDirection = Option::get($moduleId, 'WORK_DIRECTION');
		$arWorkDirection = explode(',', $workDirection);
		$arWorkDirection = array_map(function ($v) {
			return trim($v);
		}, $arWorkDirection);

		// поле ссылки проверки документов
		$linkDocumentsField = Option::get($moduleId, 'LINK_DOCUMENTS_FIELD');

		// поле с файлами
		$filesField = Option::get($moduleId, 'FILES_FIELD_NAME');

		$debugInfo = Loc::getMessage(
			'TENDER_DEBUG_INFO_METHOD',
			['#METHOD#' => __METHOD__]);
		// Выполненные сценарии обработки ИИ
		if (!empty($itemData[$statusAiField])) {
			if (is_array($itemData[$statusAiField]) && !empty(array_intersect($itemData[$statusAiField], $arStatusesAI))) {
				Debug::writeToFile(
					$debugInfo . " | Error: " . Loc::getMessage('TENDER_DEBUG_AI_WORK_PROCESS_ERROR'),
					"AI_TRACE",
					"tender_rest.log"
				);
				return;
			}
			if (!is_array($itemData[$statusAiField]) && in_array($itemData[$statusAiField], $arStatusesAI)) {
				Debug::writeToFile(
					$debugInfo . " | Error: " . Loc::getMessage('TENDER_DEBUG_AI_WORK_PROCESS_ERROR'),
					"AI_TRACE",
					"tender_rest.log"
				);
				return;
			}
		}

		// проверяем стадию
		if ($itemData['STAGE_ID'] !== $enableStage) {
			Debug::writeToFile(
				$debugInfo . " | Error: " . Loc::getMessage('TENDER_UNAVAILABLE_STAGE_ERROR'),
				"AI_TRACE",
				"tender_rest.log"
			);
			return;
		}

		// проверяем направление деятельности
		if (empty($itemData[$workDirectionField]))
		{
			Debug::writeToFile(
				$debugInfo . " | Error: " . Loc::getMessage('TENDER_EMPTY_WORK_DIRECTION_ERROR'),
				"AI_TRACE",
				"tender_rest.log"
			);
			return;
		}
		if (is_array($itemData[$workDirectionField]) && empty(array_intersect($itemData[$workDirectionField], $arWorkDirection)))
		{
			Debug::writeToFile(
				$debugInfo . " | Error: " . Loc::getMessage('TENDER_EMPTY_WORK_DIRECTION_ERROR'),
				"AI_TRACE",
				"tender_rest.log"
			);
			return;
		}
		if (!is_array($itemData[$workDirectionField]) && !in_array($itemData[$workDirectionField], $arWorkDirection))
		{
			Debug::writeToFile(
				$debugInfo . " | Error: " . Loc::getMessage('TENDER_EMPTY_WORK_DIRECTION_ERROR'),
				"AI_TRACE",
				"tender_rest.log"
			);
			return;
		}

		// проверяем ссылки на документы
		if (empty($itemData[$linkDocumentsField])) {
			if (empty($itemData[$filesField])) {
				Debug::writeToFile(
					$debugInfo . " | Error: " . Loc::getMessage('TENDER_EMPTY_FILE_FIELDS_ERROR'),
					"AI_TRACE",
					"tender_rest.log"
				);
				return;
			}
		}

		self::onAfterUpdate($item->getId());
	}
}
