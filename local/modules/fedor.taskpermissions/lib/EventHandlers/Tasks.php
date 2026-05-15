<?php

namespace Fedor\Taskpermissions\EventHandlers;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Fedor\Taskpermissions\Config;
use Fedor\Taskpermissions\Helper;

class Tasks
{
    public static function OnBeforeTaskAdd($arFields)
    {
        $iblockId = Config::getPermissionsIblockId();
        $departmentsIblock = Helper::getIblockByCode('departments', 'structure');

        $rules = [];
        $resRules = \CIBlockElement::GetList(
            [],
            ['IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'],
        false,
        false,
        ['IBLOCK_ID', 'NAME', 'ID', 'PROPERTY_FROM_DEPARTMENT', 'PROPERTY_TO_DEPARTMENT', 'PROPERTY_FROM_DEP_WITH_SUBS', 'PROPERTY_TO_DEP_WITH_SUBS']);
        while ($rule = $resRules->GetNext())
        {
            if ($rule['PROPERTY_FROM_DEP_WITH_SUBS_VALUE'] == 'Да')
            {
                $fromDepartments = Helper::getSectionAndSubSectionsIds(
                    [$rule['PROPERTY_FROM_DEPARTMENT_VALUE']],
                    $departmentsIblock
                );
            } else {
                $fromDepartments = [$rule['PROPERTY_FROM_DEPARTMENT_VALUE']];
            }

            if ($rule['PROPERTY_TO_DEP_WITH_SUBS_VALUE'] == 'Да')
            {
                $toDepartments = Helper::getSectionAndSubSectionsIds(
                    [$rule['PROPERTY_TO_DEPARTMENT_VALUE']],
                    $departmentsIblock
                );
            } else {
                $toDepartments = [$rule['PROPERTY_TO_DEPARTMENT_VALUE']];
            }

            $rules[] = [
                'FROM_DEPARTMENTS' => $fromDepartments,
                'TO_DEPARTMENTS' => $toDepartments,
            ];
        }

        $fromUserData = \CUser::GetByID($arFields['CREATED_BY'])->GetNext();
        $toUserData = \CUser::GetByID($arFields['RESPONSIBLE_ID'])->GetNext();

        if (!empty($fromUserData['UF_DEPARTMENT']) && !empty($toUserData['UF_DEPARTMENT']))
        {
            foreach ($rules as $rule)
            {
                if (
                    array_intersect($fromUserData['UF_DEPARTMENT'], $rule['FROM_DEPARTMENTS'])
                    &&
                    array_intersect($toUserData['UF_DEPARTMENT'], $rule['TO_DEPARTMENTS'])
                ) {
                    throw new \Exception('Вы не можете ставить задачу сотрууднику этого департамента!');
                }
            }
        }

        return true;
    }
}