<?php

use Bitrix\Main\Application;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

const ADMIN_MODULE_NAME = 'fedor.digitalsign_auth';

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

/** @var CMain $APPLICATION */
if ($APPLICATION->GetUserRight('fedor.digitalsign_auth') === 'D') {
	return;
}

$request = Application::getInstance()->getContext()->getRequest();
if ($request->isPost() && $request->get('REQUEST_ID') !== null) {
    $APPLICATION->IncludeComponent(
        'bitrix:ui.sidepanel.wrapper',
        '',
        [
            'POPUP_COMPONENT_NAME' => 'fedor.digitalsign_auth:admin.user-certificates.detail',
            'POPUP_COMPONENT_TEMPLATE_NAME' => '',
            'POPUP_COMPONENT_PARAMS' => [
                'REQUEST_ID' => $request->get('REQUEST_ID'),
            ],
        ]
    );
} else {
    $APPLICATION->IncludeComponent(
        'fedor.digitalsign_auth:admin.user-certificates.list',
        '',
        []
    );
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");