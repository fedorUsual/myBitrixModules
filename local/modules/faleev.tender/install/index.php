<?php
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use CAgent;
use CModule;
use Faleev\Tender\Handlers\LogHandler;

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
		$this->InstallFiles();
        $this->InstallAgents();

       return true;
    }

    public function DoUninstall(): void
    {
        $this->UnInstallEvents();
		$this->UnInstallFiles();
		$this->UnInstallAgents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

	/**
	 * @return true
	 */
	function InstallFiles(): bool
	{
		if (is_dir($p = __DIR__ . '/routes')) {
			if ($dir = opendir($p)) {
				while (false !== $item = readdir($dir)) {
					if ($item == '..' || $item == '.') {
						continue;
					}

					CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/local/routes/' . $item, $ReWrite = True, $Recursive = True);
				}
				closedir($dir);
			}
		}

		return true;
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
	        $this->MODULE_ID, '\Faleev\Tender\Handlers\LogHandler', 'onDynamicAdd'
        );
        // Смарт-процессы
        $eventManager->registerEventHandler(
            'crm', 'OnCrmDynamicItemUpdate',
	        $this->MODULE_ID, \Faleev\Tender\Handlers\LogHandler::class, 'onDynamicAdd'
        );
    }

	public function InstallAgents(): void
	{
		CAgent::AddAgent(
			"\\Faleev\\Tender\\Agent\\EmailAgent::runCron();",
			"faleev.tender",
			"N",        // агент не критичный
			60,         // интервал 60 секунд
			"",         // дата первой генерации
			"Y",        // активен
			"",         // дата первого запуска
			100,        // сортировка
			false,      // агент в кроне
			false
		);
	}

	/**
	 * @return true
	 */
	function UnInstallFiles(): bool
	{
		if (is_dir($p = __DIR__ . '/routes')) {
			if ($dir = opendir($p)) {
				while (false !== $item = readdir($dir)) {
					if ($item == '..' || $item == '.')
						continue;

					DeleteDirFilesEx('/local/routes/' . $item);
				}
				closedir($dir);
			}
		}
		return true;
	}

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('crm', 'OnAfterCrmDealAdd', $this->MODULE_ID);
        $eventManager->unRegisterEventHandler('crm', 'OnCrmDynamicItemAdd', $this->MODULE_ID);
        $eventManager->unRegisterEventHandler('crm', 'OnCrmDynamicItemUpdate', $this->MODULE_ID);
    }

	public function UnInstallAgents(): void
	{
		CAgent::RemoveAgent(
			"\\Faleev\\Tender\\Agent\\EmailAgent::runCron();",
			"faleev.tender"
		);
	}
}
