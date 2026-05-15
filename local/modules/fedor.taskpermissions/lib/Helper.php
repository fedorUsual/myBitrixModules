<?php

namespace Fedor\Taskpermissions;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Loader;

class Helper
{
    public static function getIblockByCode(string $code, string $type): int
    {
        if (Loader::includeModule('iblock')) {

            $ib = IblockTable::query()
                ->addFilter('CODE', $code)
                ->addFilter('IBLOCK_TYPE_ID', $type)
                ->addSelect('ID')
                ->setCacheTtl(3600)
                ->exec()
                ->fetchObject();

            if (!isset($ib)) {
                throw new \Exception("IB with code '{$code}' not exist!");
            }

            return $ib->getId();
        }

        throw new \Exception("Module iblock doesn't install");
    }

    /**
     * Получение идентификаторов разделов и подразделов
     * @param array $parentIds
     * @return array
     */
    public static function getSectionAndSubSectionsIds(array $parentIds, int $depsIblockId): array
    {
        $sectionsIds = $parentIds;
        $rsSection = \Bitrix\Iblock\SectionTable::getList(array(
            'order' => array('LEFT_MARGIN' => 'ASC'),
            'filter' => array(
                'IBLOCK_ID' => $depsIblockId,
                'ID' => $parentIds,
                'ACTIVE' => 'Y'
            ),
            'select' => array('ID', 'NAME', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN'),
            'cache' => array(
                'ttl' => 36000,
            )
        ));
        while ($arSection = $rsSection->fetch()) {
            $rsSubSection = \Bitrix\Iblock\SectionTable::getList(array(
                'filter' => array(
                    'IBLOCK_ID' => $depsIblockId,
                    '>LEFT_MARGIN' => $arSection['LEFT_MARGIN'],
                    '<RIGHT_MARGIN' => $arSection['RIGHT_MARGIN'],
                    '>DEPTH_LEVEL' => $arSection['DEPTH_LEVEL'],
                    'ACTIVE' => 'Y'
                ),
                'select' => array('ID'),
                'cache' => array(
                    'ttl' => 36000,
                )
            ));

            while ($arSubSection = $rsSubSection->fetch()) {
                $sectionsIds[] = $arSubSection['ID'];
            }
        }
        return $sectionsIds;
    }
}