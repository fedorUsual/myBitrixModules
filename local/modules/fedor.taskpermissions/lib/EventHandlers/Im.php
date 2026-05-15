<?php
namespace Fedor\Taskpermissions\EventHandlers;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

class Im
{
    public static function OnBeforeMessageNotifyAdd(&$arFields)
    {
        if (Loader::includeModule('im') && str_contains($arFields['NOTIFY_MESSAGE'], 'Завершите задачу или передвиньте срок')) {
            $authorId = Option::get('fedor.taskpermissions', 'NOTIFY_AUTHOR_USER_ID');
            $arFields['FROM_USER_ID'] = $authorId;
            $arFields['AUTHOR_ID'] = $authorId;
        }
    }
}