<?php
namespace Tender;

class Config
{
    const AI_SERVER_URL = 'http://62.152.34.69:8003/tenders/extraction';
    const SMART_PROCESS_ID = 1186; //1354
    const TARGET_STAGE = 'DT1186_110:PREPARATION';

    // Ключи пользовательских полей
    const FIELD_TITLE = 'TITLE';
    const FIELD_ARCHIVE_URL = 'UF_CRM_57_RUROBOT_TORGI_FILE_URL';
    const FIELD_SUMMARY = 'UF_CRM_57_1777973032163'; 
    const FIELD_ERROR = 'UF_CRM_57_1777975268210';
    // callback
    const FIELD_TABLE_TENDER = 'UF_CRM_57_1777973264727';

    const FIELD_ARCHIVE_URL_TYPE = 'array';

    public static function getHostUrl(): string
    {
        // return 'https://tandem-consult.ru';
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $protocol = $request->isHttps() ? 'https://' : 'http://';
        return $protocol . $request->getHttpHost();
    }
}
