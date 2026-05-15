<?php
/**
 * Использование современных пространств имен
 */
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;

/**
 * 1. ПОДКЛЮЧЕНИЕ ФАЙЛОВ И КОНСТАНТ
 * В стиле D7 лучше использовать абсолютные пути через Application
 */
$root = Application::getDocumentRoot();

if (file_exists($root . '/local/php_interface/include/constants.php')) {
    require_once $root . '/local/php_interface/include/constants.php';
}
if (file_exists($root . '/local/php_interface/include/functions.php')) {
    require_once $root . '/local/php_interface/include/functions.php';
}
if (file_exists($root . '/local/php_interface/include/events.php')) {
    require_once $root . '/local/php_interface/include/events.php';
}

// Старый инфоблок-календарь
if (file_exists(__DIR__ . '/CalendarInfoblocAdd.php')) {
    require_once __DIR__ . '/CalendarInfoblocAdd.php';
}

/**
 * 2. РЕГИСТРАЦИЯ РОУТИНГА (Для вашего модуля Tender)
 * Так как .settings.php в версии 26.x может капризничать, регистрируем принудительно
 */
EventManager::getInstance()->addEventHandler("main", "onAfterMainAppInit", function() {
    try {
        $router = Application::getInstance()->getRouter();
        
        // Регистрируем маршрут для тендеров
        // Используем синтаксис, максимально совместимый с ядром 26.x
        $router->get('/tender/', [\Tender\Controllers\AppController::class, 'render']);
        
    } catch (\Throwable $e) {
        // Ошибка может быть, если API роутера в 26.x изменится снова
    }
});

/**
 * 3. ОБРАБОТКА СОБЫТИЙ МЕССЕНДЖЕРА (IM)
 */
EventManager::getInstance()->addEventHandler(
    "im", 
    "OnBeforeConfirmNotify", 
    ["YNotifyEventsClass", "OnBeforeConfirmNotifyHandler"]
);

class YNotifyEventsClass
{
    /**
     * Обработчик подтверждения уведомлений (бизнес-процессы)
     */
    public static function OnBeforeConfirmNotifyHandler($module, $tag, $value, $arNotify)
    {
        // Проверка модуля и загрузка bizproc
        if ($module === "bizproc" && Loader::includeModule("bizproc")) {
            $arTag = explode("|", (string)$tag);
            
            // Исправленная логика проверки тега (убрал обрыв кода)
            if (
                count($arTag) > 3 && 
                $arTag[0] === "NOTIFYKEYBOARDACTIVITY" && 
                $arTag[1] === "CONFIRM" && 
                !empty($arTag[2]) && 
                !empty($arTag[3])
            ) {
                $arEventParameters = [
                    0 => $arTag[2],
                    [
                        'eventName'  => 'OnBeforeConfirmNotify',
                        'RESULT'     => $value,
                        'MESSAGE_ID' => $arNotify['ID'],
                        'CHAT_ID'    => $arNotify['CHAT_ID'],
                    ]
                ];

                // Отправка внешнего события в бизнес-процесс
                \CBPRuntime::SendExternalEvent($arTag[2], $arTag[3], $arEventParameters);
                
                return $value;
            }
        }
        
        return $value; // Обязательно возвращаем значение, если условие не подошло
    }
}

