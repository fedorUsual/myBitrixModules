<?php

namespace Fedor\DigitalsignAuth;

use Bitrix\Main\Config\Option;

class Module
{
    public const MODULE_ID = 'fedor.digitalsign_auth';

    public const LOG_LEVELS = [
        \CEventLog::SEVERITY_SECURITY => 1,
        \CEventLog::SEVERITY_EMERGENCY => 2,
        \CEventLog::SEVERITY_ALERT => 3,
        \CEventLog::SEVERITY_CRITICAL => 4,
        \CEventLog::SEVERITY_ERROR => 5,
        \CEventLog::SEVERITY_WARNING => 6,
        \CEventLog::SEVERITY_NOTICE => 7,
        \CEventLog::SEVERITY_INFO => 8,
        \CEventLog::SEVERITY_DEBUG => 9,
    ];

    /**
     * Записывает значение параметра модуля в БД
     * @param string $name
     * @param string $value
     * @param string $siteId
     * @return void
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function setOption(string $name, string $value, string $siteId = '')
    {
        if (self::getOption($name) != $value) {
            Option::set(self::MODULE_ID, $name, $value, $siteId);
        }
    }

    /**
     * Получает параметр модуля из БД или из значений по умолчанию (файл default_option.php)
     * @param string $name
     * @param string $siteId
     * @return string
     */
    public static function getOption(string $name, string $siteId = ''): string
    {
        // При пустом значении $default, это значение по умолчанию будет взято из файла default_option.php
        $default = '';
        return Option::get(self::MODULE_ID, $name, $default, $siteId);
    }

    /**
     * Удаляет параметр модуля из БД
     * @param string $name
     * @param string $siteId
     * @return void
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function deleteOption(string $name, string $siteId = '')
    {
        $filter = [
            'name' => $name,
            'site_id' => $siteId
        ];
        Option::delete(self::MODULE_ID, $filter);
    }

    /**
     * Удаляет все параметры модуля из БД
     * @return void
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function deleteAllOptions()
    {
        Option::delete(self::MODULE_ID, []);
    }

    /**
     * Записывает параметры модуля по умолчанию в БД
     * Параметры по умолчанию из файла default_option.php
     * @return void
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function setAllOptionsDefaults()
    {
        foreach (Option::getDefaults(self::MODULE_ID) as $name => $value) {
            Option::set(self::MODULE_ID, $name, $value);
        }
    }
}
