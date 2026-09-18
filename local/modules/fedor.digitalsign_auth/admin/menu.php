<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

global $APPLICATION;

$moduleName = "fedor.digitalsign_auth";
if (Loader::includeModule($moduleName)) {

    if ($APPLICATION->GetUserRight($moduleName) === 'D') {
	    return [];
    }

    $aModuleMenuLinks = [
	    'parent_menu' => 'global_menu_services',
	    "section" => $moduleName,
	    "sort" => 10,
	    "text" => Loc::getMessage('FEDOR_CRYPTOPROY_ADMIN_MENU'),
	    "title" => Loc::getMessage('FEDOR_CRYPTOPROY_ADMIN_MENU_TITLE'),
	    "icon" => "advertising_menu_icon",
	    "page_icon" => "advertising_page_icon",
	    "items_id" => 'menu_fedor_digitalsign_auth',
	    "items" => [
		    [
			    "text" => Loc::getMessage('FEDOR_CRYPTOPROY_ADMIN_MENU_CERTIFICATES'),
			    "title" => Loc::getMessage('FEDOR_CRYPTOPROY_ADMIN_MENU_CERTIFICATES_TITLE'),
			    "url" => "certificates_list.php?lang=" . LANGUAGE_ID,
			    "more_url" => [
				    "certificates_list.php",
			    ],
		    ],
	    ],

    ];
    return $aModuleMenuLinks;
}