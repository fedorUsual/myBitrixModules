<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Text\StringHelper;

$root = Application::getDocumentRoot();


// 1. ПОДКЛЮЧЕНИЕ ВКЛЮЧАЕМЫХ ФАЙЛОВ
foreach (['constants.php', 'functions.php', 'events.php'] as $file) {
    $path = $root . '/local/php_interface/include/' . $file;
    if (file_exists($path)) require_once $path;
}

/** todo::вообще это все включется как только подключается модуль, через автоподгрузку, но если очень надо,
 *   то лучше это подключать все в файле include.php модуля */
// 2. РЕГИСТРАЦИЯ КЛАССОВ (Только тех, что не в Composer)
//Loader::registerAutoLoadClasses(null, [
//    // Основной модуль Tender (старая структура)
//    'Tender\Config'                     => '/local/modules/faleev.tender/lib/Config.php',
//    'Faleev\Tender\Handlers\LogHandler'        => '/local/modules/faleev.tender/lib/Handlers/LogHandler.php',
//    'Tender\Controller\AjaxController'  => '/local/modules/faleev.tender/lib/Controller/AjaxController.php',
//    'Tender\Controller\AppController'   => '/local/modules/faleev.tender/lib/Controller/AppController.php',
//
//    // Модуль Tender.Bot (Классы-контроллеры, если Composer еще не прожевал dump-autoload)
//    'Tender\Bot\Controller\AjaxController' => '/local/modules/faleev.tender.bot/lib/Controller/AjaxController.php',
//    'Tender\Bot\Controller\AppController'  => '/local/modules/faleev.tender.bot/lib/Controller/AppController.php',
//]);

Loader::includeModule('faleev.faleev.tender');

//// 3. ВСПОМОГАТЕЛЬНЫЕ КЛАССЫ
//if (!class_exists('YNotifyEventsClass')) {
//    class YNotifyEventsClass {
//        public static function OnBeforeConfirmNotifyHandler($module, $tag, $value, $arNotify) {
//            if ($module === "bizproc" && Loader::includeModule("bizproc")) {
//                $arTag = explode("|", (string)$tag);
//                if (count($arTag) > 3 && $arTag[0] === "NOTIFYKEYBOARDACTIVITY" && $arTag[1] === "CONFIRM") {
//                    $arEventParameters = [
//                        $arTag[2],
//                        [
//                            'eventName'  => 'OnBeforeConfirmNotify',
//                            'RESULT'     => $value,
//                            'MESSAGE_ID' => $arNotify['ID'],
//                            'CHAT_ID'    => $arNotify['CHAT_ID'],
//                        ],
//                    ];
//                    \CBPRuntime::SendExternalEvent($arTag[2], $arTag[3], $arEventParameters);
//                    return $value;
//                }
//            }
//            return $value;
//        }
//    }
//}

// 4. ОФОРМЛЕНИЕ CRM
Asset::getInstance()->addString("
<style>
    [data-cid='UF_CRM_98_AI_SUMMARY'] .ui-entity-editor-content-block {
        white-space: pre-wrap !important;
        line-height: 1.6;
        background: #fdfdfd;
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 4px;
    }
</style>
");

//// 5. ОБРАБОТЧИКИ СОБЫТИЙ
//$eventManager = EventManager::getInstance();
//
//
//// Подтверждение в мессенджере
//$eventManager->addEventHandler(
//    "im",
//    "OnBeforeConfirmNotify",
//    ["YNotifyEventsClass", "OnBeforeConfirmNotifyHandler"]
//);
