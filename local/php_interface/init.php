<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Text\StringHelper;

$root = Application::getDocumentRoot();

// Подключаем файл окружения и парсим его, чтобы не зависеть от сторонних библиотек
$envFile = $root . '/.env';
if (file_exists($envFile)) {
	$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		if (strpos(trim($line), '#') === 0) {
			continue;
		}

		list($name, $value) = explode('=', $line, 2) + [null, null];
		if ($name !== null && $value !== null) {
			$name = trim($name);
			$value = trim($value, " \t\n\r\0\x0B\"'");

			// Записываем в окружение
			$_ENV[$name] = $value;
			$_PUTENV = putenv("$name=$value");
			$_SERVER[$name] = $value;
		}
	}
}

// Подключаем файл CalendarInfoblocAdd.php и запускаем скрипт, который лежит в файле CalendarInfoblocAdd.php
require dirname(__FILE__). '/CalendarInfoblocAdd.php';

// 1. ПОДКЛЮЧЕНИЕ ВКЛЮЧАЕМЫХ ФАЙЛОВ
foreach (['constants.php', 'functions.php', 'events.php'] as $file) {
    $path = $root . '/local/php_interface/include/' . $file;
    if (file_exists($path)) require_once $path;
}

// модуль обмена информацией с тендерами
Loader::includeModule('faleev.tender');

// 3. ВСПОМОГАТЕЛЬНЫЕ КЛАССЫ
if (!class_exists('YNotifyEventsClass')) {
    class YNotifyEventsClass {
        public static function OnBeforeConfirmNotifyHandler($module, $tag, $value, $arNotify) {
            if ($module === "bizproc" && Loader::includeModule("bizproc")) {
                $arTag = explode("|", (string)$tag);
                if (count($arTag) > 3 && $arTag[0] === "NOTIFYKEYBOARDACTIVITY" && $arTag[1] === "CONFIRM") {
                    $arEventParameters = [
                        $arTag[2],
                        [
                            'eventName'  => 'OnBeforeConfirmNotify',
                            'RESULT'     => $value,
                            'MESSAGE_ID' => $arNotify['ID'],
                            'CHAT_ID'    => $arNotify['CHAT_ID'],
                        ],
                    ];
                    \CBPRuntime::SendExternalEvent($arTag[2], $arTag[3], $arEventParameters);
                    return $value;
                }
            }
            return $value;
        }
    }
}

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

// 5. ОБРАБОТЧИКИ СОБЫТИЙ
$eventManager = EventManager::getInstance();


// Подтверждение в мессенджере
$eventManager->addEventHandler(
    "im",
    "OnBeforeConfirmNotify",
    ["YNotifyEventsClass", "OnBeforeConfirmNotifyHandler"]
);
