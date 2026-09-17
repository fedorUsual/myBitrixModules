<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

class fedor_htmlfieldtype extends CModule
{
	var $MODULE_ID = 'fedor.htmlfieldtype';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $PARTNER_NAME;
	var $PARTNER_URI;

	function __construct()
	{
		$arModuleVersion = array();

		include(__DIR__ . '/version.php');

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->MODULE_NAME = GetMessage('FEDOR_HTMLFIELDTYPE_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('FEDOR_HTMLFIELDTYPE_MODULE_DESC');

		$this->PARTNER_NAME = GetMessage('FEDOR_HTMLFIELDTYPE_PARTNER_NAME');
		$this->PARTNER_URI = GetMessage('FEDOR_HTMLFIELDTYPE_PARTNER_URI');
	}

	function DoInstall()
	{
		$this->InstallEvents();

		ModuleManager::registerModule($this->MODULE_ID);
	}

	function DoUninstall()
	{
		$this->UnInstallEvents();

		ModuleManager::unRegisterModule($this->MODULE_ID);
	}

	function InstallEvents(): bool
	{
		EventManager::getInstance()->registerEventHandler(
			'main',
			'OnUserTypeBuildList',
			$this->MODULE_ID,
			'Fedor\Htmlfieldtype\UserType\CUserTypeHtml',
			'GetUserTypeDescription'
		);
		return true;
	}

	function UnInstallEvents(): bool
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'main',
			'OnUserTypeBuildList',
			$this->MODULE_ID,
			'Fedor\Htmlfieldtype\UserType\CUserTypeHtml',
			'GetUserTypeDescription'
		);

		return true;
	}
}