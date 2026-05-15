<?php

use Bitrix\Disk\Driver;
use Bitrix\Disk\File;
use Bitrix\Disk\Folder;
use Bitrix\Disk\Internals\Error\ErrorCollection;
use Bitrix\Disk\Sharing;
use Bitrix\Main\Localization\Loc;
use \CBPActivityExecutionStatus;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class CBPFilesSharingActivity
	extends CBPActivity
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->arProperties = array(
			"Title" => "", //название действия
			"ShareToUser" => "", // кому дать права
			"RightsToFile" => "", // какие права дать
			"FileId" => "", // id файла
			"FolderId" => "", // id файла
		);
	}

	private function __GetUsers($arUsersDraft)
	{

		$arUsers = array();

		$rootActivity = $this->GetRootActivity();
		$documentId = $rootActivity->GetDocumentId();

		$documentService = $this->workflow->GetService("DocumentService");

		$arUsersDraft = (is_array($arUsersDraft) ? $arUsersDraft : array($arUsersDraft));
		$l = strlen("user_");
		foreach ($arUsersDraft as $user)
		{
			if (substr($user, 0, $l) == "user_")
			{
				$user = intval(substr($user, $l));
				if ($user > 0)
					$arUsers[] = $user;
			}
			else
			{
				$arDSUsers = $documentService->GetUsersFromUserGroup($user, $documentId);
				foreach ($arDSUsers as $v)
				{
					$user = intval($v);
					if ($user > 0)
						$arUsers[] = $user;
				}
			}
		}

		return $arUsers;
	}

	public static function validateProperties($arTestProperties = [], CBPWorkflowTemplateUser $user = null)
	{
		$arErrors = [];

		if ($user == null || !$user->isAdmin())
		{
			$arErrors[] = [
				'code' => 'perm',
				'message' => Loc::getMessage('BPCA_NO_PERMS'),
			];
		}

		return array_merge($arErrors, parent::validateProperties($arTestProperties, $user));
	}

	public function Execute()
	{

		if (!CModule::IncludeModule("disk"))
			return CBPActivityExecutionStatus::Closed;

		$authorId = 1;
		$blockedCode = 'block';
		$arErrors = [];

		// получаем пользователей для которых надо закрыть доступ с префиксом U
		$arShareToUser = $this->__GetUsers($this->ShareToUser);

		if (count($arShareToUser) <= 0) {
			return CBPActivityExecutionStatus::Closed;
		}

		$arRightsToFile = $this->RightsToFile;

		try {
			// получаем ID файла или папки на диске
			if (!empty($this->FolderId)) {
				$arFileId = is_array($this->FolderId) ? (int)current($this->FolderId) : $this->FolderId;
				$diskObject = Folder::getById($arFileId);
			} elseif (!empty($this->FileId)) {
				$arFileId = is_array($this->FileId) ? (int)current($this->FileId) : $this->FileId;
				$diskObject = File::getById($arFileId);
			}

			if (!$diskObject) {
				$this->WriteToTrackingService('File not found');
				return CBPActivityExecutionStatus::Faulting;
			}

			// получаем объект диска для дальнейшей работы через него
			$fileObject = $diskObject->getRealObject();
			$rightsManager = Driver::getInstance()->getRightsManager();

			// получаем текущие права для объекта диска и очищаем все что связано с указанными пользователями,
			// чтобы новые права не закрылись текущими, более широкими
			$resCurRights = $rightsManager->getSpecificRights($fileObject);
			$resNewRights = [];
			foreach ($arShareToUser as $userTo) {
				foreach ($resCurRights as $right) {
					if ($right['ACCESS_CODE'] === 'U' . $userTo) {
						continue;
					}
					$resNewRights[] = $right;
				}
			}
			$resSetter = $rightsManager->set($fileObject, $resNewRights);
			if (!$resSetter) {
				$arErrors[] = [
					'code' => 'emptyCode',
					'message' => 'Предыдущие права для пользователей не были удалены! Перезаписи не произошло.'
				];
			}

			// если выбрана блокировка файла/папки, то просто оставляем права затертыми,
			// в противном случае устанавливаем новые права указанным пользователям
			if ($arRightsToFile === $blockedCode) {
				if (count($arErrors) > 0) {
					$messages = array_column($arErrors, 'message');
					$this->WriteToTrackingService(GetMessage("BPSA_TRACK_ERROR") . PHP_EOL . implode(PHP_EOL, $messages));
					return CBPActivityExecutionStatus::Faulting;
				}
				return CBPActivityExecutionStatus::Closed;
			} else {
				$accessTaskId = $rightsManager->getTaskIdByName($arRightsToFile);
				$rights = [];
				foreach ($arShareToUser as $userId) {
					$rights[] = [
						'ACCESS_CODE' => 'U' . $userId,
						'TASK_ID' => $accessTaskId
					];
				}

				$res = $rightsManager->setAsNewLeaf($fileObject, $rights);
			}
		} catch (Exception $e) {
			$arErrors[] = [
				'code' => 'emptyCode',
				'message' => $e->getMessage()
			];
		}

		// запись сообщения об успешном выполнении действия в журнал бизнес-процесса
		if (count($arErrors) <= 0) {
			$this->WriteToTrackingService(GetMessage("BPSA_TRACK_OK"));
		}

		// запись сообщений об ошибке в журнал бизнес-процесса
		if (count($arErrors) > 0) {
			$messages = array_column($arErrors, 'message');
			$this->WriteToTrackingService(GetMessage("BPSA_TRACK_ERROR") . PHP_EOL . implode(PHP_EOL, $messages));
			return CBPActivityExecutionStatus::Faulting;
		}

		return CBPActivityExecutionStatus::Closed;
	}

	public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues = null, $formName = "")
	{
		$runtime = CBPRuntime::GetRuntime();

		if (!CModule::IncludeModule("socialnetwork"))
			return;

		$arMap = array(
			"ShareToUser" => "share_to_user",
			"IsFolder" => "is_folder",
			"FolderId" => "folder_id",
			"RightsToFile" => "rights_to_file",
			"FileId" => "file_id",
		);

		if (!is_array($arCurrentValues))
		{
			$arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
			if (is_array($arCurrentActivity["Properties"]))
			{
				foreach ($arMap as $k => $v)
				{
					if (array_key_exists($k, $arCurrentActivity["Properties"]))
					{
						if ($k == "ShareToUser")
							$arCurrentValues[$arMap[$k]] = CBPHelper::UsersArrayToString($arCurrentActivity["Properties"][$k], $arWorkflowTemplate, $documentType);
						else
							$arCurrentValues[$arMap[$k]] = $arCurrentActivity["Properties"][$k];
					}
					else
					{
						$arCurrentValues[$arMap[$k]] = "";
					}
				}
			}
			else
			{
				foreach ($arMap as $k => $v)
					$arCurrentValues[$arMap[$k]] = "";
			}

		}

		$rightsManager = Driver::getInstance()->getRightsManager();

		$accessREAD = $rightsManager::TASK_READ;
		$accessEDIT = $rightsManager::TASK_EDIT;
		$accessADD = $rightsManager::TASK_ADD;
		$accessFULL = $rightsManager::TASK_FULL;
		$arTaskPermission = [
			$accessREAD,
			$accessEDIT,
			$accessADD,
			$accessFULL,
			'block'
		];
		$permissions = [];
		foreach($arTaskPermission as $k => $v) {
			$permissions[$v] = GetMessage("PERMS_" . $k);
		}

		return $runtime->ExecuteResourceFile(
			__FILE__,
			"properties_dialog.php",
			array(
				"arCurrentValues" => $arCurrentValues,
				"arTaskPermission" => $permissions,
				"formName" => $formName,
			)
		);
	}

	public static function GetPropertiesDialogValues($documentType, $activityName, &$arWorkflowTemplate, &$arWorkflowParameters, &$arWorkflowVariables, $arCurrentValues, &$arErrors)
	{
		$arErrors = array();

		$arMap = array(
			"share_to_user" => "ShareToUser",
			"folder_id" => "FolderId",
			"rights_to_file" => "RightsToFile",
			"file_id" => "FileId"
		);

		$arProperties = array();
		foreach ($arMap as $key => $value)
		{
			$arProperties[$value] = $arCurrentValues[$key];
		}

		if (!empty($arCurrentValues['folder_id']) && !empty($arCurrentValues['file_id'])) {
			$arErrors[] = [
				'code' => 'emptyCode',
				'message' => GetMessage('BPSA_ONLY_ONE_FIELD_CAN_BE_ERROR'),
			];
		}

		if (!empty($arCurrentValues['folder_id']) && $arCurrentValues['rights_to_file'] === 'block') {
			$arErrors[] = [
				'code' => 'emptyCode',
				'message' => GetMessage('BPSA_ONLY_ONE_FOLDER_CAN_BE_BLOCKED_ERROR'),
			];
		}

		if (count($arErrors) > 0)
			return false;

		$arProperties["ShareToUser"] = CBPHelper::UsersStringToArray($arCurrentValues["share_to_user"], $documentType,
			$arErrors);
		if (count($arErrors) > 0)
			return false;

		$arErrors = self::validateProperties(
			$arProperties,
			new CBPWorkflowTemplateUser(CBPWorkflowTemplateUser::CurrentUser)
		);
		if (count($arErrors) > 0)
		{
			return false;
		}

		$arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
		$arCurrentActivity["Properties"] = $arProperties;
		return true;
	}
}
?>