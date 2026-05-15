<?php

namespace Fedor\TaskPermissions\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Response\AjaxJson;
use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Tasks\Integration\Forum\Task\Comment;

class Iblock extends Controller
{
    public function configureActions()
    {
        return [
            'createElement' => []
        ];
    }
    public static function createElementAction(array $params)
    {
        if (!empty($params['new_deadline_date'])
            && !empty($params['task_deadline_id'])
            && !empty($params['user_id'])
            && !empty($params['new_deadline_reason'])
        ) {

            $iblockId = \Fedor\Taskpermissions\Helper::getIblockByCode(
                'request-for-move-deadline',
                'lists'
            );

            $elementId = self::createIblockElement($iblockId, $params);
            if (!is_numeric($elementId)) {
                $error = new Error(' Не удалось создать элемент инфоблока по причине: ' . $elementId);
                $result = new Result();
                $result->addError($error);
                return AjaxJson::createError($result->getErrorCollection());
            }

            if (Loader::IncludeModule('bizproc'))
            {
                $bizproc = self::startBizproc($iblockId, $elementId);

                if (!($bizproc['success'])) {
                    $error = new Error($bizproc['errorMessage']);
                    $result = new Result();
                    $result->addError($error);
                    return AjaxJson::createError( $result->getErrorCollection() );
                }
            }

            if (Loader::includeModule('tasks') && Loader::includeModule('bizproc')) {


                if (!empty($params['current_deadline']) && $params['current_deadline'] !== 'null') {
                    $comment = 'Отправил запрос Постановщику на перенос крайнего срока с '
                        . date('d.m.Y H:i', strtotime($params['current_deadline']))
                        . ' на ' . date('d.m.Y H:i', strtotime($params['new_deadline_date']));
                } else {
                    $comment = 'Отправил запрос Постановщику об установке крайнего срока'
                        . ' на ' . date('d.m.Y H:i', strtotime($params['new_deadline_date']));
                }
                $res = self::addCommentToTask(
                    $params['user_id'],
                    $params['task_deadline_id'],
                    $comment);
                if (is_array($res)) {
                    $error = new Error('Не получилось добавить комментарий');
                    $result = new Result();
                    $result->addError($error);
                    return AjaxJson::createError($result->getErrorCollection());
                }
            }
        } else {
            $error = new Error('Не заполнен комментарий к запросу или новый крайний срок! Запрос не создан!');
            $result = new Result();
            $result->addError($error);
            return AjaxJson::createError($result->getErrorCollection());
        }

        return ['new_element' => $elementId];
    }

    private static function createIblockElement($iblockId, array $params): string|int
    {
        $props = [
            'NOVYY_KRAYNIY_SROK' => date('d.m.Y H:i:s', strtotime($params['new_deadline_date'])),
            'PRICHINA_PERENOSA' => $params['new_deadline_reason'],
            'POSTANOVSHCHIK_ZADACHI' => $params['created_by'],
            'KTO_ZAPROSIL' => $params['user_id'],
            'SSYLKA_NA_ZADACHU' => '<a href="/company/personal/user/'
                . $params['user_id']
                . '/tasks/task/view/'
                . $params['task_deadline_id']
                . '/">Задача №'. $params['task_deadline_id'] . '</a>',
            'ID_ZADACHI' => $params['task_deadline_id']
        ];

        if (!empty($params['current_deadline']) && $params['current_deadline'] !== 'null') {
            $props['TEKUSHCHIY_KRAYNIY_SROK'] = date('d.m.Y H:i:s', strtotime($params['current_deadline']));
        }
        $arFields = [
            'NAME' => 'Запрос',
            'IBLOCK_ID' => $iblockId,
            'CODE' => strtotime($params['new_deadline_date']),
            'PROPERTY_VALUES' => $props
        ];

        $el = new \CIBlockElement();
        $elementId = $el->Add($arFields, true);

        if (!$elementId) {
            return $el->LAST_ERROR;
        }

        return $elementId;
    }

    private static function startBizproc($iblockId, $elementId): array
    {
        $result = [
            'success' => true,
            'errorMessage' => ''
        ];

        $arWorkflowTemplates = \CBPDocument::GetWorkflowTemplatesForDocumentType(array("lists", "Bitrix\Lists\BizprocDocumentLists","iblock_".$iblockId));

        if (count($arWorkflowTemplates) <= 0) {
            $result['success'] = false;
            $result['errorMessage'] = 'У данного инфоблока отсутствуют шаблоны';
            return $result;
        }

        $errorMessage = '';
        foreach ($arWorkflowTemplates as $arTemplate) {
            /**
             * AUTO_EXECUTE = 1 - запускать при создании
             * AUTO_EXECUTE = 2 - запускать при изменении
             */
            if ($arTemplate['AUTO_EXECUTE'] == 1) {
                $arWorkflowParameters = [];
                $arErrorsTmp = [];
                $wfId = \CBPDocument::StartWorkflow
                (
                    $arTemplate['ID'],
                    array("lists", "Bitrix\Lists\BizprocDocumentLists", $elementId),
                    array_merge($arWorkflowParameters, array("TargetUser" => "user_" . intval($GLOBALS["USER"]->GetID()))),
                    $arErrorsTmp
                );

                if (count($arErrorsTmp) > 0) {
                    foreach ($arErrorsTmp as $e) {
                        $errorMessage .= "[" . $e["code"] . "] " . $e["message"] . "";
                    }
                } elseif (is_null($wfId)) {
                    $errorMessage .= "Неправильные параметры для запуска бизнес-процесса";
                }

            }

        }

        if (!empty($errorMessage)) {
            $result['success'] = false;
            $result['errorMessage'] = $errorMessage;
            return $result;
        }

        return $result;
    }

    private static function addCommentToTask($userId, $taskId, string $comment)
    {

        $result = Comment::add($taskId, [
            'AUTHOR_ID' => $userId,
            'POST_MESSAGE' => $comment,
        ]);

        if (!$result->isSuccess()) {
            return $result->getErrors()->toArray();
        }

        return true;
    }
}