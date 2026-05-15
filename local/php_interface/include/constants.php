<?php
/**
 * Связь смарт-процесса "Организация встреч" с календарем
 */
define('ENTITY_TYPE_ID', 137);
define('EVENT_TIME_FIELD_CODE', 'ufCrm17_1718898712'); //дата и время проведения
define('EVENT_LINK_FIELD_CODE', 'UF_CRM_17_1721318901'); // ссылка на событие в календаре
define('EVENT_LOCATION_FIELD_CODE', 'ufCrm17_1717578017'); // место проведения
define('EVENT_DESCRIPTION_FIELD_CODE', 'ufCrm17_1717580859'); // описание собрания
define('EVENT_ID_FIELD_CODE', 'ufCrm17_1721675572973'); // id события в календаре, если оно уже создано и связано с этим смарт-процессом
define('EVENT_ID_FIELD_CODE_UPPER_CASE', 'UF_CRM_17_1721675572973');
define('EVENT_NEEDED_TO_CREATE_FIELD_CODE', 'ufCrm17_1721824502');
define('EVENT_NEEDED_TO_CREATE_VALUE_CODE', '586');
define('EVENT_REMIND_MINUTES', 15);
define('EVENT_REMIND_HOURS', 1);