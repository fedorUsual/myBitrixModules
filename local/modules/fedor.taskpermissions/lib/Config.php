<?php

namespace Fedor\Taskpermissions;

class Config
{
    public static function getPermissionsIblockId(): int
    {
        return Helper::getIblockByCode('TASK_CREATE_FORBIDDENS', 'rest_entity');
    }
}