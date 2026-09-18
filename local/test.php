<?php

use Bitrix\Bizproc\Workflow\Entity\WorkflowUserTable;
use Bitrix\Bizproc\Workflow\Task\TaskTable;
use Bitrix\Bizproc\Workflow\Task\TaskUserTable;
use Bitrix\Crm\Service\Container;
use Bitrix\Crm\Service\Factory;
use Bitrix\Disk\Driver;
use Bitrix\Disk\File;
use Bitrix\Disk\Folder;
use Bitrix\Disk\Internals\Error\ErrorCollection;
use Bitrix\Disk\Sharing;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$title = 'AI ассистент';
$handler = getenv('AI_ASSIST_URL');
$data = [
	[
		'method' => 'placement.bind',
		'params' => [
			"PLACEMENT" => getenv('ITR_APPLICATION_AI_PLACEMENT'),
			"HANDLER"   => $handler,
			"TITLE"     => $title,
		]
	],
	[
		'method' => 'placement.bind',
		'params' => [
			"PLACEMENT" => getenv('LEFT_MENU_AI_PLACEMENT'),
			"HANDLER"   => $handler,
			"TITLE"     => $title,
		]
	],
	[
		'method' => 'placement.bind',
		'params' => [
			"PLACEMENT" => getenv('TASK_VIEW_AI_PLACEMENT'),
			"HANDLER"   => $handler,
			"TITLE"     => $title,
		]
	],
	[
		'method' => 'placement.bind',
		'params' => [
			"PLACEMENT" => getenv('ITR_PROJECTS_AI_PLACEMENT'),
			"HANDLER"   => $handler,
			"TITLE"     => $title,
		]
	],
	[
		'method' => 'placement.bind',
		'params' => [
			"PLACEMENT" => getenv('COMPANY_AI_PLACEMENT'),
			"HANDLER"   => $handler,
			"TITLE"     => $title,
		]
	],
];

print '<pre>' . print_r($data, true) . '</pre>';
die();
$moduleId = 'faleev.tender';
\Bitrix\Main\Loader::includeModule('faleev.tender');
$itemData = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/local/test_3.json'), true)[1];
$options = Option::getForModule('faleev.tender');
$smartId = Option::get($moduleId, 'SMART_PROCESS_ID');
$entityId = 1186;

// получаем допустимые значения поля "Выполненные сценарии обработки ИИ"
$statusAiField = Option::get($moduleId, 'AI_WORK_STATUS_FIELD');
$statusAI = Option::get($moduleId, 'AI_WORK_STATUS');
$arStatusesAI = explode(',', $statusAI);
$arStatusesAI = array_map(function ($v) {
	return trim($v);
}, $arStatusesAI);

// получаем допустимую для отправки в ИИ стадию заявки
$enableStage = Option::get($moduleId, 'ENABLE_STAGE');

// получаем допустимые значения поля "Направление деятельности"
$workDirectionField = Option::get($moduleId, 'WORK_DIRECTION_FIELD');
$workDirection = Option::get($moduleId, 'WORK_DIRECTION');
$arWorkDirection = explode(',', $workDirection);
$arWorkDirection = array_map(function ($v) {
	return trim($v);
}, $arWorkDirection);

// поле ссылки проверки документов
$linkDocumentsField = Option::get($moduleId, 'LINK_DOCUMENTS_FIELD');

// поле с файлами
$filesField = Option::get($moduleId, 'FILES_FIELD_NAME');

if ($entityId === (int)$smartId) {
	// Выполненные сценарии обработки ИИ
	if (!empty($itemData[$statusAiField])) {
		if (is_array($itemData[$statusAiField]) && !empty(array_intersect($itemData[$statusAiField], $arStatusesAI))) {
			echo 'Найдены совпадения массива массивов';
			die();
		}
		if (!is_array($itemData[$statusAiField]) && in_array($itemData[$statusAiField], $arStatusesAI)) {
			echo 'Найдена строка статуса в массиве';
			die();
		}
	}

	// проверяем стадию
	if ($itemData['STAGE_ID'] !== $enableStage) {
		echo 'Стадия не соотвествует заявленной';
		die();
	}

	// проверяем направление деятельности
	if (empty($itemData[$workDirectionField]))
	{
		echo 'Направление деятельности не заполнено';
		die();
	}
	if (is_array($itemData[$workDirectionField]) && empty(array_intersect($itemData[$workDirectionField], $arWorkDirection)))
	{
		echo 'not array in array';
		die();
	}
	if (!is_array($itemData[$workDirectionField]) && !in_array($itemData[$workDirectionField], $arWorkDirection))
	{
		echo 'not string in array';
		die();
	}

	// проверяем ссылки на документы
	if (empty($itemData[$linkDocumentsField])) {
		if (empty($itemData[$filesField])) {
			echo 'Ни поле, ни что либо другое не заполнено';
			die();
		}
	}

	echo 'Запрос отправлен';
}

die();
\Bitrix\Main\Loader::includeModule('crm');

$json = '{
    "entities": [
      {
        "ENTITY_TYPE": "COMPANY",
        "ENTITY_ID": 3450,
        "IS_DUPLICATE": false
      },
      {
        "ENTITY_TYPE": "DYNAMIC_189",
        "ENTITY_ID": 602,
        "IS_DUPLICATE": false
      },
      {
        "ENTITY_TYPE": "ACTIVITY",
        "ENTITY_ID": 12622,
        "IS_DUPLICATE": false
      }
    ]
  }';
$datas = json_decode($json, true);
addUserWhoCreate($datas);

$className = '\\Fedor\\Taskpermissions\\EventHandlers\\FormsHandler';
Bitrix\Main\EventManager::getInstance()->registerEventHandler(
	'crm',
	'onSiteFormFilled',
	'fedor.taskpermissions',
	$className,
	'addUserWhoCreate'
);
//print '<pre>' . print_r(var_dump($result->isSuccess()), true) . '</pre>';
//print '<pre>' . print_r($result->getErrorMessages(), true) . '</pre>';

//print '<pre>' . print_r($item, true) . '</pre>';
//print '<pre>' . print_r($document->offsetGet('CRM_ID'), true) . '</pre>';
//print '<pre>' . print_r($fields, true) . '</pre>';

die();
$paramsJson = '{
    "id": 17318,
    "primary": {
      "ID": 17318
    },
    "fields": {
      "STATE": "Y",
      "APPROVED": true,
      "SORT": 150,
      "START_DATE": {},
      "LAST_POST_DATE": {},
      "POSTS_UNAPPROVED": 0,
      "ABS_LAST_POST_DATE": {},
      "TITLE": "WF_68a75bfe16aa23.09634995",
      "TAGS": "",
      "FORUM_ID": 42,
      "USER_START_ID": 0,
      "USER_START_NAME": "\u0421\u0438\u0441\u0442\u0435\u043c\u0430",
      "LAST_POSTER_NAME": "\u0421\u0438\u0441\u0442\u0435\u043c\u0430",
      "XML_ID": "WF_68a75bfe16aa23.09634995",
      "TITLE_SEO": "wf_68a75bfe16aa23.09634995"
    },
    "object": {}
  }';
global $APPLICATION;

$params = json_decode($paramsJson, true);
$topicId = $params['id'];
$topicData = $params['fields'];
$forumId = $topicData['FORUM_ID'];
$workflowId = str_replace('WF_', '', $topicData['XML_ID']);
$resWorkflowAuthor = WorkflowUserTable::query()
	->setFilter(['WORKFLOW_ID' => $workflowId, 'IS_AUTHOR' => true])
	->setSelect(['WORKFLOW_ID', 'IS_AUTHOR', 'USER_ID'])
	->fetch();
$forumSubscribe = CForumSubscribe::GetList([], [
	'USER_ID' => $resWorkflowAuthor['USER_ID'],
	'TOPIC_ID' => $topicId,
])->GetNext();
if (!$forumSubscribe) {
	$fields = [
		'USER_ID' => 479,
		'FORUM_ID' => $forumId,
		'TOPIC_ID' => $topicId,
		'START_DATE' => (new DateTime())->modify('-5 minutes'),
		'SITE_ID' => SITE_ID,
	];
	var_dump(CForumSubscribe::CheckFields("ADD", $fields));
	$res = CForumSubscribe::Add($fields);
	if (!$res) {
		echo $APPLICATION->LAST_ERROR;
	}
	var_dump($res);
}
    print '<pre>' . print_r($topicData, true) . '</pre>';
    print '<pre>' . print_r($resWorkflowAuthor, true) . '</pre>';
    print '<pre>' . print_r($forumSubscribe, true) . '</pre>';
//}
