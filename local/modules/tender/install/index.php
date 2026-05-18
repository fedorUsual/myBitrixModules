<?php
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

class tender extends CModule // Имя класса обычно совпадает с ID модуля в нижнем регистре
{
    var $MODULE_ID = 'tender';
    var $MODULE_VERSION = '1.0.0';
    var $MODULE_VERSION_DATE = '2026-10-10 10:00:00';
    var $MODULE_NAME = 'Faleev Tender SPA';
    var $MODULE_DESCRIPTION = 'React-приложение на D7 роутинге';

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
            'crm', 'OnAfterCrmDynamicItemAdd', 
            $this->MODULE_ID, '\Tender\Handlers\Crm', 'onSmartProcessCreate'
        );
    }

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('crm', 'OnAfterCrmDealAdd', $this->MODULE_ID);
        $eventManager->unRegisterEventHandler('crm', 'OnAfterCrmDynamicItemAdd', $this->MODULE_ID);
    }
}
