<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
/** @global $APPLICATION */

//ссылка подключает файл именно из главного модуля, если данный модуль не главный, то не надо менять на подключение из
// текущего. Менять нужно только в случае, если главный модуль по другому называется
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/fedor.taskpermissions/functions_for_options_page.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/fedor.taskpermissions/LabelWithPlaceholdersSpoiler.php';


$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
$module_id_fields = $module_id . '_fields';

Loader::includeModule($module_id);

$aTabsStatic = array(
    array(
        "DIV" => "general",
        "TAB" => GetMessage("FEDOR_TASKPERMISSIONS_TAB_GENERAL"),
        "TITLE" => GetMessage("FEDOR_TASKPERMISSIONS_TAB_GENERAL"),
        "OPTIONS" => array(
            array(
                "NOTIFY_AUTHOR_USER_ID",
                GetMessage("FEDOR_TASKPERMISSIONS_NOTIFY_AUTHOR_USER_ID"),
                "",
                array('text', 50)
            ),
            array(
                "WORKFLOW_TEMPLATE_ID",
                GetMessage("FEDOR_TASKPERMISSIONS_WORKFLOW_TEMPLATE_ID"),
                "",
                array('text', 11)
            ),
            array(
                "WORKFLOW_LIST_ELEMENT_ID",
                GetMessage("FEDOR_TASKPERMISSIONS_WORKFLOW_LIST_ELEMENT_ID"),
                "",
                array('text', 11)
            ),
        ),
    )
);

//region динамически добавляемые настройки
$arOptionValues = Option::getForModule($module_id);
$arOptionFields = Option::getForModule($module_id_fields);

$sortedOptionFields = [];
foreach ($arOptionFields as $optionKey => $fieldInfo) {
    [$tabName, $remainedFieldInfo] = explode("#", $fieldInfo, 2);
    $sortedOptionFields[$tabName][$optionKey] = $remainedFieldInfo;
}

$aTabs = $aTabsStatic;

if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) {

        foreach ($aTab["OPTIONS"] as $arOption) {

            if (!is_array($arOption)) {
                continue;
            }

            if ($arOption["note"]) {

                continue;
            }

            $optionName = $arOption[0];

            if ($request["apply"]) {

                $optionValue = $request->getPost($optionName);
                if (in_array($optionName, ['ACTIVE'])) {//тут все булевы
                    if ($optionValue == "") {
                        $optionValue = "N";
                    }
                }

                if (!empty($optionValue || $optionValue === 0)) {
                    Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
                } else {
                    Option::delete($module_id, array("name" => $optionName));
                    Option::delete($module_id_fields, array("name" => $optionName));
                }

            } elseif ($request["default"]) {
                Option::set($module_id, $optionName, $arOption[2]);
            }
        }

        if (!empty($request["new_field_title"])) {
            foreach ($request["new_field_title"] as $tabId => $newTitleFields) {
                foreach ($newTitleFields as $i => $v) {
                    if (
                        strlen($request["new_field_block"][$tabId][$i]) > 0 &&
                        strlen($request["new_field_title"][$tabId][$i]) > 0 &&
                        strlen($request["new_field_name"][$tabId][$i]) > 0 &&
                        strlen($request["new_field_value"][$tabId][$i]) > 0
                    ) {
                        Option::set($module_id, $request["new_field_name"][$tabId][$i], $request["new_field_value"][$tabId][$i]);
                        Option::set($module_id_fields, $request["new_field_name"][$tabId][$i], $tabId . '#' . $request["new_field_block"][$tabId][$i] . '#' . $_POST["new_field_title"][$tabId][$i]);
                    }
                }
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $module_id . "&lang=" . LANG);
} ?>

<?php
$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs,
    false
);

$tabControl->Begin();
?>
<form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>"
      method="post">

    <?

    foreach ($aTabs as $aTab) {

        $tabControl->BeginNextTab();
        if ($aTab["DIV"] != "rights") { // не особая (обычная) вкладка
            if ($aTab["OPTIONS"]) {
                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }
        } elseif ($aTab["DIV"] == "rights") { // суперкостыль для правки прав, потому что в битриксе вся форма подключается в отдельном файле
            require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");
        }
    }

    $tabControl->Buttons();
    ?>

    <input type="submit" name="apply" value="<?= GetMessage("MAIN_SAVE") ?>" class="adm-btn-save"/>

    <?
    echo(bitrix_sessid_post());
    ?>

</form>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<? $tabControl->End(); ?>
