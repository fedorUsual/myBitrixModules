<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var \CMain $APPLICATION */
/** @var array $arResult */

CJSCore::init("sidepanel");

$columns = $arResult['COLUMNS'];
$rows = $arResult['ROWS'];
$filter = $arResult['FILTER'];

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => 'user_certificates_list_filter',
        'GRID_ID' => 'user_certificates_list',
        'FILTER' => $filter,
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true,
    ]
);

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => 'user_certificates_list',
        'COLUMNS' => $columns,
        'ROWS' => $rows,
        'AJAX_MODE' => 'Y',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_HISTORY' => 'N',
    ]
);
