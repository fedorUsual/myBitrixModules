<?php
namespace Faleev\Tender\Controller;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;

class AppController extends Controller
{
	protected static string $moduleId = 'faleev.tender';
    public function configureActions()
    {
        return [
            'test' => [
                '-prefilters' => [ActionFilter\Csrf::class, ActionFilter\Authentication::class],
            ],
            'subscribe' => [
                '-prefilters' => [ActionFilter\Csrf::class, ActionFilter\Authentication::class],
            ],
        ];
    }

    /**
     * Рендерим интерфейс. 
     * Рекомендуется в index.php фронтенда не использовать глобальные переменные напрямую.
     */
    public function renderAction()
    {
        $documentRoot = Application::getDocumentRoot();
        $interfacePath = $documentRoot . '/local/modules/faleev.faleev.tender/frontend/index.php';

        if (file_exists($interfacePath)) {
            ob_start();
            // Передаем контекст контроллера внутрь файла, если нужно
            $controller = $this; 
            include $interfacePath;
            return ob_get_clean();
        }

        return "Интерфейс модуля Tender не найден.";
    }

    public function testAction()
    {
		$options = Option::getForModule(self::$moduleId);
        return [
            'status' => 'online',
            'config_check' => [
                'smart_process_id' => $options['smart_process_id'],
                'field_summary' => $options['field_summary'],
            ],
            'server_time' => date('H:i:s'),
        ];
    }

    /**
     * Регистрация событий. 
     * ВАЖНО: Вызывать этот метод нужно ОДИН раз (например, при установке модуля или через URL)
     */
    public function subscribeAction()
    {
        if (!Loader::includeModule('crm')) {
            return ['status' => 'error', 'message' => 'CRM module not found'];
        }
	    $options = Option::getForModule(self::$moduleId);
        $entityTypeId = (int)$options['smart_process_id'];
        if ($entityTypeId <= 0) {
            return ['status' => 'error', 'message' => 'Invalid SMART_PROCESS_ID in Config'];
        }

        $eventManager = EventManager::getInstance();
        $moduleId = 'faleev.faleev.tender';
        $handlerClass = "\\Faleev\\Faleev\\Tender\\Handlers\\LogHandler";

        $events = [
            "OnAfterCrmDynamicItemAdd_" . $entityTypeId => "onAfterAdd",
            "OnAfterCrmDynamicItemUpdate_" . $entityTypeId => "onAfterUpdate",
            "OnAfterCrmDynamicItemDelete_" . $entityTypeId => "onAfterDelete", // Полезно добавить
        ];

        $results = [];
        foreach ($events as $eventName => $methodName) {
            // Проверка: не зарегистрировано ли уже это событие, чтобы не дублировать в БД
            $alreadyRegistered = false;
            $handlers = $eventManager->findEventHandlers("crm", $eventName);
            foreach ($handlers as $handler) {
                if ($handler['TO_CLASS'] == $handlerClass && $handler['TO_METHOD'] == $methodName) {
                    $alreadyRegistered = true;
                    break;
                }
            }

            if (!$alreadyRegistered) {
                $eventManager->registerEventHandler(
                    "crm",
                    $eventName,
                    $moduleId,
                    $handlerClass,
                    $methodName
                );
                $results[] = "Registered: $eventName";
            } else {
                $results[] = "Already exists: $eventName";
            }
        }

        return [
            'status' => 'success',
            'details' => $results
        ];
    }
}
