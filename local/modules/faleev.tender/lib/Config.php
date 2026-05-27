<?php
namespace Faleev\Tender;

use Bitrix\Main\Context;

class Config
{
    const SMART_PROCESS_ID = 1186; //1354

    // Ключи пользовательских полей
    const FIELD_TITLE = 'TITLE';

    public static function getHostUrl(): string
    {
        $request = Context::getCurrent()->getRequest();
        $protocol = $request->isHttps() ? 'https://' : 'http://';
        return $protocol . $request->getHttpHost();
    }
}
