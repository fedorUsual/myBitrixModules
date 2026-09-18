<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\IO;
use Bitrix\Main\SiteTable;
use Bitrix\Main\SiteTemplateTable;
use Bitrix\Main\SystemException;
use Bitrix\Main\UserFieldTable;
use Fedor\DigitalsignAuth\Handlers\Users;

Loc::loadMessages(__FILE__);

if(class_exists("fedor_digitalsign_auth")) return;

class fedor_digitalsign_auth extends CModule
{
	var $MODULE_ID = 'fedor.digitalsign_auth';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;

	public array $errors;
	private $exclusionAdminFiles = [
		'..',
		'.',
		'menu.php',
	];
	private string $MODULE_DIR;

	private string $ORM_PATH = '/lib/ORM';
	private string $NAME_SPASE = 'Fedor\DigitalsignAuth\ORM';

	private const USER_FIELDS = [
		'UF_SNEELS' => [
			'ENTITY_ID' => 'USER',
			'FIELD_NAME' => 'UF_SNEELS',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_SNEELS',
			'SORT' => 100,
			'MULTIPLE' => 'N',
			'MANDATORY' => 'Y',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
			'SETTINGS' => [
				'SIZE' => 20,
				'ROWS' => 1,
				'MIN_LENGTH' => 0,
				'MAX_LENGTH' => 14,
				'REGEXP' => '',
				'DEFAULT_VALUE' => '',
			],
			'EDIT_FORM_LABEL' => ['ru' => 'СНИЛС', 'en' => 'SNEELS'],
			'LIST_COLUMN_LABEL' => ['ru' => 'СНИЛС', 'en' => 'SNEELS'],
			'LIST_FILTER_LABEL' => ['ru' => 'СНИЛС', 'en' => 'SNEELS'],
			'ERROR_MESSAGE' => ['ru' => 'Некорректный формат СНИЛС', 'en' => 'Invalid SNEELS format'],
		],
	];

	public function __construct()
	{
		$arModuleVersion = [];

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path . "/version.php");

		$this->MODULE_NAME = Loc::getMessage("FEDOR_CRYPTOPRO_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("FEDOR_CRYPTOPRO_DESCRIPTION");
		$this->PARTNER_NAME = Loc::getMessage("FEDOR_CRYPTOPRO_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("FEDOR_CRYPTOPRO_PARTNER_URI");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		}

		$this->MODULE_DIR = '/local/modules/' . $this->MODULE_ID;
	}

	public function DoInstall()
	{
		global $APPLICATION, $CACHE_MANAGER;

		if (!class_exists('CPSigner')) {
			$APPLICATION->ThrowException(
				Loc::getMessage("FEDOR_CRYPTOPRO_INSTALL_ERROR_CRYPTOPRO")
			);
		}

		if (!CheckVersion(ModuleManager::getVersion("main"), "19.00.00")) {
			$APPLICATION->ThrowException(
				Loc::getMessage("FEDOR_CRYPTOPRO_INSTALL_ERROR_VERSION")
			);
		}

		try {
			$this->checkAgreementFormForSite();
			$this->installUserFields();
			$this->InstallDB();
			$this->InstallFiles();

			RegisterModule($this->MODULE_ID);

			$this->InstallTables();
			$this->InstallEvents();
			$this->InstallAgents();
			$CACHE_MANAGER->CleanAll();
			BXClearCache(true);
		} catch (Exception $e) {
			$this->DoUninstall();
			throw new RuntimeException(Loc::getMessage('FEDOR_CRYPTOPRO_MODULE_INSTALL_IS_BREAK') . $e->getMessage(), 0, $e);
		}
	}

	public function DoUninstall()
	{
		global $APPLICATION, $CACHE_MANAGER;

		try {
			$this->UnInstallEvents();
			$this->UnInstallAgents();
			$this->UnInstallFiles();
			$this->UnInstallTables();
			$this->uninstallUserFields();
			$this->UnInstallDB();

			UnRegisterModule($this->MODULE_ID);

			$CACHE_MANAGER->CleanAll();
			BXClearCache(true);

			$APPLICATION->includeAdminFile(
				Loc::getMessage("FEDOR_CRYPTOPRO_UNINSTALL_TITLE") . " \"" . Loc::getMessage("FEDOR_CRYPTOPRO_NAME") . "\"",
				__DIR__ . "/unstep.php"
			);
		} catch (Exception $e) {
			$this->DoInstall();
			throw new RuntimeException(Loc::getMessage('FEDOR_CRYPTOPRO_MODULE_UNINSTALL_IS_BREAK') . $e->getMessage(), 0, $e);
		}


		return true;

	}

	public function InstallDB()
	{
		Loader::includeModule($this->MODULE_ID);

		$siteId = $this->getDefaultSiteLid();

		$siteCondition = [
			'SITE_ID' => $siteId,
			'CONDITION' => 'defined("NEED_AUTH") && NEED_AUTH === true && !$USER->IsAuthorized()',
			'SORT' => 10,
			'TEMPLATE' => 'auth_template',
		];

		SiteTemplateTable::add($siteCondition);

		return true;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	function InstallFiles(): bool
	{
		if (IO\Directory::isDirectoryExists($path = __DIR__ . '/admin')) {
			if ($dir = opendir($path)) {
				while (false !== $item = readdir($dir)) {
					if (isset($this->exclusionAdminFiles) && in_array($item, $this->exclusionAdminFiles))
						continue;

					CopyDirFiles($path . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $item, true, true);
					if (!is_writable($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $item)) {
						throw new Exception(Loc::getMessage(
							'FEDOR_CRYPTOPRO_COPY_FILE_ERROR',
							['#FILE_PATH#' => $path . '/' . $item]
						));
					}
				}
				closedir($dir);
			}
		}

		if (is_dir($p = __DIR__ . '/components')) {
			if ($dir = opendir($p)) {
				while (false !== $item = readdir($dir)) {
					if ($item == '..' || $item == '.'){
						continue;
					}

					CopyDirFiles($p.'/'.$item, $_SERVER['DOCUMENT_ROOT'].'/local/components/'.$this->MODULE_ID.'/'.$item, $ReWrite = True, $Recursive = True);
					if (!is_writable($_SERVER['DOCUMENT_ROOT'].'/local/components/'.$this->MODULE_ID.'/'.$item)) {
						throw new Exception(Loc::getMessage(
							'FEDOR_CRYPTOPRO_COPY_FILE_ERROR',
							['#FILE_PATH#' => $p.'/'.$item]
						));
					}
				}
				closedir($dir);
			}
		}

		CopyDirFiles(__DIR__ . '/templates', $_SERVER['DOCUMENT_ROOT'] . '/local/templates', true, true);
		if (!is_writable($_SERVER['DOCUMENT_ROOT'] . '/local/templates')) {
			throw new Exception(Loc::getMessage(
				'FEDOR_CRYPTOPRO_COPY_FILE_ERROR',
				['#FILE_PATH#' => __DIR__ . '/templates']
			));
		}
		return true;
	}

	public function InstallTables()
	{
		Loader::includeModule($this->MODULE_ID);

		$tables = $this->getTables();

		foreach ($tables as $table) {
			if (is_string($table)) {
				$table = new $table();
			}

			if ($table instanceof DataManager) {
				if (!Application::getConnection()->isTableExists($table::getTableName())) {
					$table::getEntity()->createDbTable();
				}
			}
		}

		return true;
	}

	public function InstallAgents()
	{
		return true;
	}

	public function InstallEvents()
	{
		$eventManager = EventManager::getInstance();
		$eventManager->registerEventHandler(
			'main',
			'OnUserLoginExternal',
			$this->MODULE_ID,
			Users::class,
			'onUserLoginExternal'
		);

		$eventManager->registerEventHandler(
			'main',
			'OnExternalAuthList',
			$this->MODULE_ID,
			Users::class,
			'OnExternalAuthList'
		);

		return true;
	}

	public function UnInstallAgents()
	{
		return true;
	}

	public function UnInstallTables()
	{
		Loader::includeModule($this->MODULE_ID);

		$tables = $this->getTables();

		foreach ($tables as $table) {
			if (is_string($table)) {
				$table = new $table();
			}

			if ($table instanceof DataManager) {
				if (Application::getConnection()->isTableExists($table::getTableName())) {
					Application::getConnection()->dropTable($table::getTableName());
				}
			}
		}

		return true;
	}

	function UnInstallFiles()
	{
		if (is_dir($p = __DIR__ . '/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;

					DeleteDirFilesEx('/local/components/'.$this->MODULE_ID);
				}
				closedir($dir);
			}
		}

		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID.'/install/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$item);
				}
				closedir($dir);
			}
		}

		DeleteDirFilesEx('local/templates/auth_template');
	}

	public function UnInstallDB()
	{
		$res = SiteTemplateTable::getList(['filter' => ['TEMPLATE' => 'auth_template']]);
		$templates = $res->fetchAll();
		foreach ($templates as $template) {
			SiteTemplateTable::delete($template['ID']);
		}

		Option::delete($this->MODULE_ID);
	}

	public function UnInstallEvents()
	{
		$eventManager = EventManager::getInstance();
		$eventManager->unRegisterEventHandler(
			'main',
			'OnUserLoginExternal',
			$this->MODULE_ID,
			Users::class,
			'onUserLoginExternal'
		);

		$eventManager->unRegisterEventHandler(
			'main',
			'OnExternalAuthList',
			$this->MODULE_ID,
			Users::class,
			'OnExternalAuthList'
		);
	}

	/**
	 * Проверка директории
	 * @throws Exception
	 */
	private function findOrmClasses(): array
	{
		$ormClasses = [];
		$basePath = $_SERVER['DOCUMENT_ROOT'] . $this->MODULE_DIR . $this->ORM_PATH;

		if (!is_dir($basePath)) {
			throw new Exception(GetMessage('FEDOR_MODULE_ORM_DIRECTORY_NOT_FOUND', ['PATH' => $basePath]));
		}

		$this->scanDirectoryRecursive($basePath, $basePath, $ormClasses);

		return $ormClasses;
	}

	/**
	 * Поиск сущностей
	 * @param string $currentPath
	 * @param string $basePath
	 * @param array $ormClasses
	 * @return void
	 */
	private function scanDirectoryRecursive(string $currentPath, string $basePath, array &$ormClasses): void
	{
		$items = scandir($currentPath);
		if ($items === false) {
			return;
		}

		foreach ($items as $item) {
			if ($item === '.' || $item === '..') {
				continue;
			}

			$fullPath = $currentPath . '/' . $item;

			if (is_dir($fullPath)) {
				$this->scanDirectoryRecursive($fullPath, $basePath, $ormClasses);
			} elseif (pathinfo($item, PATHINFO_EXTENSION) === 'php') {
				$relativePath = substr($fullPath, strlen($basePath) + 1);
				$className = basename($relativePath, '.php');
				$namespacePath = dirname($relativePath);

				if ($namespacePath === '.') {
					$fullClassName = $this->NAME_SPASE . '\\' . $className;
				} else {
					$namespace = str_replace('/', '\\', $namespacePath);
					$fullClassName = $this->NAME_SPASE . '\\' . $namespace . '\\' . $className;
				}

				if (class_exists($fullClassName)) {
					$reflection = new ReflectionClass($fullClassName);

					if (
						$reflection->isSubclassOf('Bitrix\Main\Entity\DataManager') &&
						!$reflection->isAbstract()
					) {
						$ormClasses[] = $fullClassName;
					}
				}
			}
		}
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	function getTables(): array
	{
		return $this->findOrmClasses();
	}

	/**
	 * Создаёт UF-поля, если их ещё нет.
	 * При любой ошибке откатывает транзакцию и выбрасывает исключение.
	 *
	 * @throws SystemException
	 */
	public function installUserFields(): void
	{
		$connection = Application::getConnection();
		$connection->startTransaction();

		try {
			foreach (self::USER_FIELDS as $fieldName => $fieldData) {
				$entityId = $fieldData['ENTITY_ID'];
				if (self::getFieldId($fieldName, $entityId) !== null) {
					continue;
				}

				$userTypeEntity = new CUserTypeEntity();
				$id = $userTypeEntity->Add($fieldData);

				if (!$id) {
					throw new SystemException(
						Loc::getMessage(
							'FEDOR_CRYPTOPRO_UF_INSTALL_ERROR',
							['#FIELD_NAME#' => $fieldName, '#REASON#' => self::getLastErrorText()]
						)
					);
				}
			}

			$connection->commitTransaction();
		} catch (Throwable $e) {
			$connection->rollbackTransaction();
			throw new SystemException(
				$e->getMessage(),
				(int)$e->getCode(),
				$e
			);
		}
	}

	/**
	 * Удаляет UF-поля, если они есть.
	 * При любой ошибке откатывает транзакцию и выбрасывает исключение.
	 *
	 * @throws SystemException
	 */
	public function uninstallUserFields(): void
	{
		$connection = Application::getConnection();
		$connection->startTransaction();

		try {
			foreach (array_keys(self::USER_FIELDS) as $fieldName) {
				$entityId = self::USER_FIELDS[$fieldName]['ENTITY_ID'];
				$fieldId = self::getFieldId($fieldName, $entityId);

				if ($fieldId === null) {
					continue;
				}

				$userTypeEntity = new CUserTypeEntity();
				$result = $userTypeEntity->Delete($fieldId);

				if (!$result) {
					throw new SystemException(
						Loc::getMessage(
							'FEDOR_CRYPTOPRO_UF_UNINSTALL_ERROR',
							[
								'#FIELD_NAME#' => $fieldName,
								'#FIELD_ID#' => $fieldId,
								'#REASON#' => self::getLastErrorText()]
						)
					);
				}
			}

			$connection->commitTransaction();
		} catch (Throwable $e) {
			$connection->rollbackTransaction();

			throw new SystemException(
				$e->getMessage(),
				(int)$e->getCode(),
				$e
			);
		}
	}

	/**
	 * Возвращает ID пользовательского поля по имени и названию сущности
	 * @param string $fieldName
	 * @param string $entityId
	 * @return int|null
	 * @throws Exception
	 */
	private static function getFieldId(string $fieldName, string $entityId): ?int
	{
		$resUserTypeEntity = UserFieldTable::query();
		$res = $resUserTypeEntity
			->setFilter([
				'ENTITY_ID' => $entityId,
				'FIELD_NAME' => $fieldName,
			])
			->setSelect(['ID', 'FIELD_NAME'])
			->fetch();
		if (empty($res)) {
			return null;
		}

		return (int)$res['ID'];
	}

	/**
	 * Проверяет наличие пользовательского соглашения для сайта
	 * @return void
	 * @throws Exception
	 */
	private function checkAgreementFormForSite(): void
	{
		$siteId = $this->getDefaultSiteLid();
		$formAgreementId = Option::get('main', 'new_user_agreement', '', $siteId);
		if (empty($formAgreementId)) {
			throw new Exception(Loc::getMessage('FEDOR_CRYPTOPRO_USER_CONSEST_NOT_FOUND'));
		}
	}

	/**
	 * Обрабатывает исключения старых методов битрикса.
	 * @return string
	 */
	private static function getLastErrorText(): string
	{
		global $APPLICATION;

		$exception = $APPLICATION->GetException();

		return $exception ? $exception->GetString() : Loc::getMessage('FEDOR_CRYPTOPRO_UNKNOWN_ERROR');
	}

	/**
	 * Возвращает ID текущего дефолтного сайта
	 * @return string
	 * @throws SystemException
	 * @throws Exception
	 */
	private function getDefaultSiteLid(): string
	{
		$siteDefault = SiteTable::query()
			->addFilter('DEF', 'Y')
			->setSelect(['LID', 'NAME', 'DEF'])
			->fetch();

		return $siteDefault['LID'] ?? 's1';
	}
}