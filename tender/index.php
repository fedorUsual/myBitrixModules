<?php
// Если это запрос от робота или колбэк от ИИ — отключаем проверки прав
if (isset($_REQUEST['document_id']) || isset($_GET['action'])) {
    define("NO_KEEP_STATISTIC", true);
    define("NOT_CHECK_PERMISSIONS", true);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Faleev\Tender\Controller\AppController;
use Faleev\Tender\Controller\AjaxController;

if (Loader::includeModule('faleev.tender')) {
	var_dump(class_exists('Faleev\Tender\Controller\AjaxController'));
    $ajax = new \Faleev\Tender\Controller\AjaxController();

    // 1. ОБРАБОТКА CALLBACK ОТ ИИ (Прием данных)
    // URL: /faleev.tender/index.php?action=callback
    if ($_GET['action'] === 'callback') {
        $APPLICATION->RestartBuffer();
        header('Content-Type: application/json');
        echo json_encode($ajax->callbackAction());
        die();
    }

    // 2. Запрос на получение актуальных данных из CRM ОТ ИИ (Прием данных)
    // URL: /faleev.tender/index.php?action=get_data
    if ($_GET['action'] === 'get_data') {
        $APPLICATION->RestartBuffer();
        header('Content-Type: application/json');
        echo json_encode($ajax->getDataAction());
        die();
    }

    // 3. Создание карточки в смарт процессе (Trigger, Hook создание объекта)
    // URL: /faleev.tender/index.php?action=callback_email
    if ($_GET['action'] === 'callback_email') {
        $APPLICATION->RestartBuffer();
        header('Content-Type: application/json');
        echo json_encode($ajax->callbackEmailAction());
        die();
    }

	/** todo:: удалить полностью, если все будет отрабатывать нормально без вебхука*/
	/*// 4. ОБРАБОТКА SYNC ОТ РОБОТОВ/СЦЕНАРИЕВ (Отправка в ИИ)
	// URL: /faleev.tender/index.php?document_id={{ID}}
	if (isset($_REQUEST['document_id'])) {
		$APPLICATION->RestartBuffer();
		header('Content-Type: application/json');
		echo json_encode($ajax->syncAction());
		die();
	}*/

    // 5. ОТОБРАЖЕНИЕ ИНТЕРФЕЙСА (Для человека)
    echo (new AppController())->renderAction();

} else {
    echo "Ошибка: Модуль 'faleev.tender' не установлен.";
}

//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

