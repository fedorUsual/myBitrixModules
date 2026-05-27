<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
/** @global $APPLICATION */

$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
$module_id_fields = $module_id . '_fields';

Loader::includeModule($module_id);

$aTabsStatic = array(
    array(
        "DIV" => "general",
        "TAB" => GetMessage("TENDER_TAB_GENERAL"),
        "TITLE" => GetMessage("TENDER_TAB_GENERAL"),
        "OPTIONS" => array(
            array(
                "TENDER_MAIN_DOMAIN",
                GetMessage("TENDER_MAIN_DOMAIN"),
                "",
                array('text', 50)
            ),
            array(
                "AI_SERVER_URL",
                GetMessage("TENDER_AI_SERVER_URL"),
                "",
                array('text', 50)
            ),
            array(
                "AI_PROCESS_EMAIL_URL",
                GetMessage("TENDER_AI_PROCESS_EMAIL_URL"),
                "",
                array('text', 50)
            ),
            array(
                "EMAIL_POLL_INTERVAL_MINUTES",
                GetMessage("TENDER_EMAIL_POLL_INTERVAL_MINUTES"),
                "15",
                array('text', 10)
            ),
            array(
                "IMAP_HOST",
                GetMessage("TENDER_IMAP_HOST"),
                "",
                array('text', 50)
            ),
            array(
                "IMAP_PORT",
                GetMessage("TENDER_IMAP_PORT"),
                "993",
                array('text', 10)
            ),
            array(
                "IMAP_USER",
                GetMessage("TENDER_IMAP_USER"),
                "",
                array('text', 50)
            ),
            array(
                "IMAP_PASSWORD",
                GetMessage("TENDER_IMAP_PASSWORD"),
                "",
                array('password', 50)
            ),
            array(
                "IMAP_MAILBOX",
                GetMessage("TENDER_IMAP_MAILBOX"),
                "INBOX",
                array('text', 50)
            ),
            array(
                "TARGET_STAGE_ID",
                GetMessage("TENDER_TARGET_STAGE_ID"),
                "",
                array('text', 50)
            ),
            array(
                "FIELD_ARCHIVE_URL",
                GetMessage("TENDER_FIELD_ARCHIVE_URL"),
                "",
                array('text', 50)
            ),
            array(
                "FIELD_SUMMARY",
                GetMessage("TENDER_FIELD_SUMMARY"),
                "",
                array('text', 50)
            ),
            array(
                "FIELD_ERROR",
                GetMessage("TENDER_FIELD_ERROR"),
                "",
                array('text', 50)
            ),
            array(
                "FIELD_TABLE_TENDER",
                GetMessage("TENDER_FIELD_TABLE_TENDER"),
                "",
                array('text', 50)
            ),
            array(
                "FIELD_REQUEST_TYPE",
                GetMessage("TENDER_FIELD_REQUEST_TYPE"),
                "UF_CRM_57_RUROBOT_TORGI_TYPE_ZAKUPKI",
                array('text', 50)
            ),
            array(
                "FIELD_ACTIVITY_DIRECTION",
                GetMessage("TENDER_FIELD_ACTIVITY_DIRECTION"),
                "UF_CRM_57_1752592467",
                array('text', 50)
            ),
            array(
                "FIELD_DESCRIPTION",
                GetMessage("TENDER_FIELD_DESCRIPTION"),
                "UF_CRM_57_RUROBOT_TORGI_DESCR",
                array('text', 50)
            ),
            array(
                "FIELD_END_USER_INN",
                GetMessage("TENDER_FIELD_END_USER_INN"),
                "UF_CRM_57_RUROBOT_TORGI_INN_ZAKAZCHIKA",
                array('text', 50)
            ),
            array(
                "FIELD_END_USER_NAME",
                GetMessage("TENDER_FIELD_END_USER_NAME"),
                "UF_CRM_57_RUROBOT_TORGI_ZAKAZCHIK",
                array('text', 50)
            ),
            array(
                "FIELD_LOT_NUMBER",
                GetMessage("TENDER_FIELD_LOT_NUMBER"),
                "UF_CRM_57_RUROBOT_TORGI_NUMBER_ZAKUPKI",
                array('text', 50)
            ),
            array(
                "FIELD_TKP_DEADLINE",
                GetMessage("TENDER_FIELD_TKP_DEADLINE"),
                "UF_CRM_57_RUROBOT_TORGI_STOP_DATE",
                array('text', 50)
            ),
            array(
                "FIELD_TENDER_FILES_URL",
                GetMessage("TENDER_FIELD_TENDER_FILES_URL"),
                "UF_CRM_57_RUROBOT_TORGI_FILE_URL",
                array('text', 50)
            ),
            array(
                "FIELD_DOCUMENTS",
                GetMessage("TENDER_FIELD_DOCUMENTS"),
                "UF_CRM_57_RUROBOT_TORGI_FILE",
                array('text', 50)
            ),
            array(
                "FIELD_SCORING",
                GetMessage("TENDER_FIELD_SCORING"),
                "UF_CRM_57_1757054330",
                array('text', 50)
            ),
            array(
                "DEFAULT_CATEGORY_ID",
                GetMessage("TENDER_DEFAULT_CATEGORY_ID"),
                "65",
                array('text', 50)
            ),
            array(
                "DEFAULT_STAGE_ID",
                GetMessage("TENDER_DEFAULT_STAGE_ID"),
                "",
                array('text', 50)
            ),
            array(
                "DEFAULT_RESPONSIBLE_ID",
                GetMessage("TENDER_DEFAULT_RESPONSIBLE_ID"),
                "563",
                array('text', 50)
            ),
            array(
                "TRIGGER_CODE",
                GetMessage("TENDER_TRIGGER_CODE"),
                "",
                array('text', 50)
            ),
            array(
                "SECRET",
                GetMessage("TENDER_SECRET"),
                "",
                array('text', 50)
            ),
            array(
                "UF_CRM_57_1779356556019",
                GetMessage("UF_CRM_57_1779356556019"),
                "UF_CRM_57_1779356556019",
                array('text', 50)
            ),
            GetMessage("SMAC_SMART_OPTIONS_FOR_AI_REQUEST"),
            array(
                "SMART_PROCESS_ID",
                GetMessage("TENDER_SMART_PROCESS_ID"),
                "",
                array('text', 50)
            ),
            array(
                "AI_WORK_STATUS_FIELD",
                GetMessage("TENDER_AI_WORK_STATUS_FIELD"),
                "",
                array('text', 50)
            ),
            array(
                "AI_WORK_STATUS",
                GetMessage("TENDER_AI_WORK_STATUS"),
                "",
                array('text', 50)
            ),
            array(
                "ENABLE_STAGE",
                GetMessage("TENDER_ENABLE_STAGE"),
                "",
                array('text', 50)
            ),
            array(
                "WORK_DIRECTION_FIELD",
                GetMessage("TENDER_WORK_DIRECTION_FIELD"),
                "",
                array('text', 50)
            ),
            array(
                "WORK_DIRECTION",
                GetMessage("TENDER_WORK_DIRECTION"),
                "",
                array('text', 50)
            ),
            array(
                "LINK_DOCUMENTS_FIELD",
                GetMessage("TENDER_LINK_DOCUMENTS_FIELD"),
                "",
                array('text', 50)
            ),
            array(
                "FILES_FIELD_NAME",
                GetMessage("TENDER_FILES_FIELD_NAME"),
                "",
                array('text', 50)
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
