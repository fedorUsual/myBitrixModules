<?php
// Экономим ресурсы и отключаем проверки для API
if (isset($_REQUEST['document_id']) || isset($_GET['action'])) {
    define("NO_KEEP_STATISTIC", true);
    define("NOT_CHECK_PERMISSIONS", true);
    define("BX_CRONTAB", true);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;

// 1. Пытаемся подключить автозагрузку Composer
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/vendor/autoload.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"].'/local/vendor/autoload.php');
}

// 2. Если модуль не установлен, регистрируем классы вручную (дублируем PSR-4)
Loader::registerAutoLoadClasses(null, [
    'Tender\Bot\Controller\AjaxController' => '/local/modules/tender.bot/lib/Controller/AjaxController.php',
    'Tender\Bot\Controller\AppController'  => '/local/modules/tender.bot/lib/Controller/AppController.php',
    'Tender\Bot\Telegram'                  => '/local/modules/tender.bot/lib/Telegram.php',
    'Tender\Bot\Handler'                   => '/local/modules/tender.bot/lib/Handler.php',
]);

try {
    // Проверяем существование класса перед инициализацией
    if (!class_exists('\Tender\Bot\Controller\AjaxController')) {
        throw new \Exception("Класс \Tender\Bot\Controller\AjaxController не найден. Проверьте путь и namespace.");
    }

    $ajax = new \Tender\Bot\Controller\AjaxController();

    // ПРИЕМ CALLBACK
    if (($_GET['action'] ?? '') === 'callback') {
        $APPLICATION->RestartBuffer();
        header('Content-Type: application/json');
        echo json_encode($ajax->callbackAction());
        die();
    }

    // ОТПРАВКА SYNC
    if (isset($_REQUEST['document_id'])) {
        $APPLICATION->RestartBuffer();
        header('Content-Type: application/json');
        echo json_encode($ajax->syncAction());
        die();
    }

    // ИНТЕРФЕЙС
    if (class_exists('\Tender\Bot\Controller\AppController')) {
        $app = new \Tender\Bot\Controller\AppController();
        echo $app->renderAction();
    } else {
        echo "Контроллер интерфейса не найден.";
    }

} catch (\Throwable $e) {
    // Выводим подробности ошибки прямо на экран (пока отлаживаем)
    echo "<div style='color:red; padding:20px; border:1px solid'> ";
    echo "<b>Ошибка:</b> " . $e->getMessage() . "<br>";
    echo "<b>Файл:</b> " . $e->getFile() . ":" . $e->getLine();
    echo "</div>";
    
    Debug::writeToFile($e->getMessage() . " in " . $e->getFile(), "TENDER_BOT_FATAL", "tender_bot_debug.log");
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
