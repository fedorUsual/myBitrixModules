<?php


namespace Bestrank\CrmDependentFields\Handlers;

use Bitrix\Main\Localization\Loc;
use Bestrank\CrmDependentFields\Helper;

class BuildGlobalMenu
{
	public static function addMenuItem(&$aGlobalMenu, &$aModuleMenu)
	{
		global $USER;
		global $APPLICATION;

		if (!$USER->IsAdmin() || (isset($aGlobalMenu['global_menu_bestrank']) && is_array($aGlobalMenu['global_menu_bestrank'])))
			return;

		$aGlobalMenu['global_menu_bestrank'] = [
			"menu_id" => "bestrank",
			"text" => Loc::getMessage('GLOBAL_MENU_BESTRANK_TEXT'),
			"title" => Loc::getMessage('GLOBAL_MENU_BESTRANK_TITLE'),
			"sort" => 1000,
			"items_id" => "global_menu_bestrank",
			"items" => []
		];

		$APPLICATION->SetAdditionalCSS("/bitrix/css/bestrank.globalmenu/style.css");
	}
}