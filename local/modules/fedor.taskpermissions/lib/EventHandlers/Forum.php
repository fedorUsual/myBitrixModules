<?php

namespace Fedor\Taskpermissions\EventHandlers;

use Bitrix\Bizproc\Workflow\Task;
use Bitrix\Crm\Service\Container;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\TaskTable;
use CBPRuntime;
use CCrmOwnerType;
use CIBlockElement;
use CIBlockElementRights;
use Exception;

class Forum
{
	/**
	 * Метод запускает бизнес-процесс для обработки сообщений добавляемых в чат другого бизнес-процесса
	 * @throws Exception
	 */
	public static function onAfterMessageAdd($ID, $arMessage, $arMessageTopic, $arMessageForum, $arFields): void
	{

		if (!Loader::includeModule('bizproc') || !Loader::includeModule('fedor.taskpermissions'))
		{
			return;
		}

		$workflowTemplateId = Option::get('fedor.taskpermissions', 'WORKFLOW_TEMPLATE_ID');
		$workflowListElementId = Option::get('fedor.taskpermissions', 'WORKFLOW_LIST_ELEMENT_ID');

		if (empty($workflowTemplateId) || empty($workflowListElementId)) {
			return;
		}

		$wfIdWithPref = str_starts_with($arMessage['XML_ID'], 'WF_') ? $arMessage['XML_ID'] : '';
		if (empty($wfIdWithPref)) {
			return;
		}

		$authorId = $arMessage['AUTHOR_ID'];
		if (empty($authorId)) {
			return;
		}

		preg_match_all('/\[USER=(\d+)\]/', $arMessage['POST_MESSAGE'], $matches);
		$users = $matches[1] ?? [];

		$runtime = CBPRuntime::getRuntime();
		$workflowId = str_replace('WF_', '', $wfIdWithPref);
		$currentWf = $runtime->GetWorkflow($workflowId, true);
		$wfId = $currentWf->getDocumentId();
		$startedByUserId = $currentWf->getStartedBy();

		if (empty($wfId)) {
			return;
		}

		$errors = [];

		if (!empty($users)) {
			if ($wfId[0] === 'lists') {
				$resElement = CIBlockElement::GetByID($wfId[2])->GetNext();
				$iblockId = $resElement['IBLOCK_ID'];
				$elementId = $resElement['ID'];
				foreach ($users as $userTag) {
					self::addRightsToUserIfNot($userTag, $iblockId, $elementId);
				}
			}
			/** TODO: пока не используется, возможно, в будущем передумают*/
			/*
			if ($wfId[0] === 'crm') {
				self::crmTypesBizprocProcessing($users, $wfId[2], $workflowId);
			}*/
		}

		$resBpTaskId = Task\TaskTable::getList(['filter' => ['WORKFLOW_ID' => $workflowId]])->fetch();
		$bpTaskId = $resBpTaskId['ID'] ?? '';

		$str = $runtime->createWorkflow(
			$workflowTemplateId,
			['lists', 'BizprocDocument', $workflowListElementId],
			[
				'initiator' => !empty($startedByUserId) ? 'user_' . $startedByUserId : '',
				'author' => 'user_' . $authorId,
				'user_teg' => array_map(function ($v) {
					return 'user_' . $v;
				}, $users),
				'message' => $arMessage['POST_MESSAGE'],
				'WORKFLOW_ID' => str_replace('WF_', '', $wfIdWithPref),
				'entity' => $wfId[2],
				'BP_TASK_ID' => $bpTaskId,
			],
			$errors
		);
		$str->start();
	}


	/**
	 * TODO: пока не используется, возможно, в будущем передумают
	 * Метод добавляет сотрудников в участники Бизнес-процесса для crm сущностей.
	 * @param array $users
	 * @param string $documentId
	 * @param string $workflowId
	 * @return void
	 * @throws Exception
	 */
	private static function crmTypesBizprocProcessing(array $users, string $documentId, string $workflowId): void
	{
		$documentInfo = self::getCrmDocumentInfo($documentId);
		[$entityTypeId, $entityId] = [$documentInfo['TYPE_ID'], $documentInfo['ID']];
		if (empty($entityTypeId) || empty($entityId)) {
			return;
		}
		$container = Container::getInstance();
		$factory = $container->getFactory($entityTypeId);
		$item = $factory?->getItem($entityId);

		$taskData = Task\TaskTable::getList([
			'filter' => ['WORKFLOW_ID' => $workflowId],
			'select' => ['ID']
		])->fetch();
		$observers = $item->getObservers();
		foreach ($users as $userId)
		{
			if ($taskData && is_array($taskData))
			{
				global $DB;
				$arFields = [
					'USER_ID' => $userId,
					'TASK_ID' => $taskData['ID'],
					'ORIGINAL_USER_ID' => $userId
				];
				$arInsert = $DB->PrepareInsert("b_bp_task_user", $arFields);
				$strSql =
					"INSERT INTO b_bp_task_user (".$arInsert[0].") ".
					"VALUES(".$arInsert[1].")";
				$DB->Query($strSql);
			}

			if (!in_array($userId, $observers)) {
				$observers[] = $userId;
			}
		}

		$item->setObservers($observers);
		$updateOperation = $factory->getUpdateOperation($item, $container->getContext()->setUserId(1));

		$result = $updateOperation->launch();
		if (!$result->isSuccess()) {
			throw new Exception(implode(PHP_EOL, $result->getErrorMessages()));
		}
	}

	/**
	 * Метод дает права чтения для элемента, если их до этого не было дано пользователю
	 * @param int|string $userId
	 * @param int|string $iblockId
	 * @param int|string $elementId
	 * @return void
	 * @throws Exception
	 */
	private static function addRightsToUserIfNot(int|string $userId, int|string $iblockId, int|string $elementId): void
	{
		$task = TaskTable::getList([
			"select" => ["ID"],
			"filter" => ["=LETTER" => 'R', "=MODULE_ID" => "iblock", "=SYS" => "Y"],
		])->fetch();
		$rightId = (!empty($task) ? $task["ID"] : null);
		$rights = new CIBlockElementRights($iblockId, $elementId);

		$hasRights = false;
		$allRights = $rights->GetRights();
		foreach ($allRights as $right) {
			if ($right['GROUP_CODE'] === 'U'.$userId) {
				$hasRights = true;
				break;
			}
		}

		if (!$hasRights) {
			$allRights['n0'] = [
				"GROUP_CODE"  => 'U'.$userId,
				"DO_INHERIT" => "Y",
				"IS_INHERITED" => "N",
				"OVERWRITED" => 0,
				"TASK_ID" => $rightId,
				"XML_ID" => null,
				"ENTITY_TYPE" => "element",
				"ENTITY_ID" => 13162,
			];
			$rights->SetRights($allRights);
		}
	}

	private static function getCrmDocumentInfo($documentId): false|array
	{
		$documentIdParts = explode('_', $documentId);
		$typePrefix = $documentIdParts[0] ?? null;

		if (count($documentIdParts) > 2) {
			$entityTypeId = $documentIdParts[1] ?? null;
			$entityId = $documentIdParts[2] ?? null;
		} else {
			$entityTypeId = CCrmOwnerType::ResolveID($typePrefix) ?? null;
			$entityId = $documentIdParts[1] ?? null;
		}

		if (is_null($entityTypeId))
		{
			return false;
		}

		return [
			'TYPE' => $typePrefix . '_' . $entityTypeId,
			'TYPE_ID' => (int)$entityTypeId,
			'ID' => (int)$entityId,
		];
	}
}
