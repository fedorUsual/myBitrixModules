<?php
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

class faleev_tender extends CModule // Имя класса обычно совпадает с ID модуля в нижнем регистре
{
    public $MODULE_ID = 'faleev.tender';
	public $MODULE_VERSION = '1.0.0';
	public $MODULE_VERSION_DATE = '2026-10-10 10:00:00';
    public $MODULE_NAME = 'Faleev Tender SPA';
    public $MODULE_DESCRIPTION = 'React-приложение на D7 роутинге';

	public function __construct()
	{
		$arModuleVersion = [];

		include __DIR__ . '/version.php';
	}

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();

		return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallEvents()
    {
        $eventManager = EventManager::getInstance();
        
        // Сделки
        $eventManager->registerEventHandler(
            'crm', 'OnAfterCrmDealAdd',
	        $this->MODULE_ID, '\Tender\Handlers\Crm', 'onDealCreate'
        );

        // Смарт-процессы
        $eventManager->registerEventHandler(
            'crm', 'OnCrmDynamicItemAdd',
	        $this->MODULE_ID, '\Tender\Handlers\LogHandler', 'onDynamicAdd'
        );
    }

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('crm', 'OnAfterCrmDealAdd', $this->MODULE_ID);
        $eventManager->unRegisterEventHandler('crm', 'OnCrmDynamicItemAdd', $this->MODULE_ID);
    }
}
