<?php

/**
 * Bitrix vars
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global CDatabase $DB
 * @global string $mid
 */

$module_id = 'fedor.digitalsign_auth';

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\GroupTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SiteTable;
use Fedor\DigitalsignAuth\Helpers\GeneralHelper;
use Fedor\DigitalsignAuth\Module;


if (!Loader::includeModule($module_id)) {
    return;
}

$context = Application::getInstance()->getContext();
$request = $context->getRequest();
$docRoot = $context->getServer()->getDocumentRoot();

Loc::loadMessages($docRoot . BX_ROOT . "/modules/main/options.php");
Loc::loadMessages(__FILE__);

if (CMain::GetGroupRight($module_id) < "S") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}


$result = GroupTable::getList(array(
    'select'  => ["ID", "NAME"], // выберем название, идентификатор, символьный код, сортировку
    'filter'  => ['!ID' => '1'] // все группы, кроме основной группы администраторов
));

$options = Option::getForModule($module_id);
if (empty($options)) {
    $options = Option::getDefaults($module_id);
}

// проверяем указанные в опции сайты и если находим новые, то добавляем к ним опцию по авторизации
$siteDefault = $options['fedor_cryptopro_usefull_sites'];
if (empty($siteDefault)) {
    $siteDefault = GeneralHelper::getDefaultSiteLid();
}

$arGroups = [];
while ($arGroup = $result->fetch()) {
    $arGroups[$arGroup["ID"]] = "[".$arGroup["ID"]."] ".$arGroup["NAME"];
}
$arTypeAuth = ['cryptopro' =>Loc::getMessage("FEDOR_CRYPTOPRO_TYPES_AUTH_auth_crypto")];
/**
 * Tabs data
 */
$tabs = [
    [
        "DIV" => "server",
        "TAB" => Loc::getMessage("FEDOR_CRYPTOPRO_TAB_SERVER_NAME"),
        "TITLE" => Loc::getMessage("FEDOR_CRYPTOPRO_TAB_SERVER_TITLE"),
        "OPTIONS" => [
            [
                0 => 'FEDOR_CRYPTOPRO_TYPES_AUTH',
                1 =>  Loc::getMessage("FEDOR_CRYPTOPRO_TYPES_AUTH"),
                2 => $options['FEDOR_CRYPTOPRO_TYPES_AUTH'],
                3 =>  ['multiselectbox',$arTypeAuth],
                4 => 'N',
                5 => '',
                6 => 'N',
            ],
            [
                0 => 'FEDOR_CRYPTOPRO_USER_REGISTRATION',
                1 => Loc::getMessage("FEDOR_CRYPTOPRO_USER_REGISTRATION"),
                2 => $options['FEDOR_CRYPTOPRO_USER_REGISTRATION'],
                3 => ['checkbox'],
                4 => 'N',
                5 => '',
                6 => 'N',
            ],
            [
                0 => 'FEDOR_CRYPTOPRO_IS_SEND_ERROR',
                1 => Loc::getMessage("FEDOR_CRYPTOPRO_IS_SEND_ERROR"),
                2 => $options['FEDOR_CRYPTOPRO_IS_SEND_ERROR'],
                3 => ['checkbox'],
                4 => 'N',
                5 => '',
                6 => 'N',
            ],
            [
                0 => 'FEDOR_CRYPTOPRO_USEFULL_SITES',
                1 => Loc::getMessage("FEDOR_CRYPTOPRO_USEFULL_SITES"),
                2 => $siteDefault ?? '',
                3 => ['textarea', 3, 40],
                4 => 'N',
                5 => Loc::getMessage("FEDOR_CRYPTOPRO_USEFULL_SITES_separate"),
                6 => 'N',
            ],
            [
                'note' => Loc::getMessage("FEDOR_CRYPTOPRO_NOTE"),
            ],
            [
                0 => 'FEDOR_CRYPTOPRO_ACCESS_USERLOGIN_INN',
                1 => Loc::getMessage("FEDOR_CRYPTOPRO_ACCESS_USERLOGIN_INN"),
                2 => $options['FEDOR_CRYPTOPRO_ACCESS_USERLOGIN_INN'],
                3 =>  ['textarea', 15, 40],
                4 => 'N',
                5 => Loc::getMessage("FEDOR_CRYPTOPRO_ACCESS_USERLOGIN_INN_separate"),
                6 => 'N',
            ],
            [
                0 => 'FEDOR_CRYPTOPRO_ADD_GROUPS',
                1 => Loc::getMessage("FEDOR_CRYPTOPRO_ADD_GROUPS"),
                2 => $options['FEDOR_CRYPTOPRO_ADD_GROUPS'],
                3 =>  ['multiselectbox',$arGroups],
                4 => 'N',
                5 => '',
                6 => 'N',
            ],
            [
                0 => 'FEDOR_CRYPTOPRO_ADD_GROUPS_NO_COMPYNY',
                1 => Loc::getMessage("FEDOR_CRYPTOPRO_ADD_GROUPS_NO_COMPYNY"),
                2 => $options['FEDOR_CRYPTOPRO_ADD_GROUPS_NO_COMPYNY'],
                3 => ['multiselectbox', $arGroups],
                4 => 'N',
                5 => Loc::getMessage("FEDOR_CRYPTOPRO_ADD_GROUPS_NO_COMPYNY_note"),
                6 => 'N',
            ],

        ],
    ],
    [
        "DIV" => "logger",
        "TAB" => Loc::getMessage("FEDOR_CRYPTOPRO_TAB_LOGGER_NAME"),
        "TITLE" => Loc::getMessage("FEDOR_CRYPTOPRO_TAB_LOGGER_TITLE"),
        "OPTIONS" => [
            [
                0 => 'FEDOR_CRYPTOPRO_LOG_LEVEL',
                1 => Loc::getMessage("FEDOR_CRYPTOPRO_LOG_LEVEL"),
                2 => $options['FEDOR_CRYPTOPRO_LOG_LEVEL'],
                3 => ['selectbox', array_flip(Module::LOG_LEVELS)],
                4 => 'N',
                5 => '',
                6 => 'N',
            ],
        ],
    ],
];

if (CMain::GetGroupRight($module_id) === "W") {
    $tabs[] = [
        "DIV" => "access",
        "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS"),
    ];
}

$tabControl = new CAdminTabControl('settings', $tabs);

/**
 * Request
 */
$backUrl = $request->get('back_url_settings');

$Update = $request->getPost('Save') . $request->getPost('Apply');
if ($request->isPost() && check_bitrix_sessid()) {
    $breakSave = false;
    if (!empty($Update)) {
        $arrRequest = $request->toArray();
        if (isset($arrRequest['FEDOR_CRYPTOPRO_USEFULL_SITES'])) {
            if ($arrRequest['FEDOR_CRYPTOPRO_USEFULL_SITES'] !== $siteDefault) {
                $newSites = explode(',', $arrRequest['FEDOR_CRYPTOPRO_USEFULL_SITES']);
                $oldSites = explode(',', $siteDefault);
                try {
                    GeneralHelper::addRemoveSiteTemplateOptions($newSites, $oldSites);
                } catch (Exception $e) {
                    CAdminMessage::ShowMessage($e->getMessage());
                    $breakSave = true;
                }
            }
        }
        if (!$breakSave) {
            foreach ($tabs as $tab) {
                __AdmSettingsSaveOptions($module_id, $tab['OPTIONS']);
            }

            if (CMain::GetGroupRight($module_id) === "W") {
                ob_start();
                require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");
                ob_end_clean();
            }
        }
    } elseif (isset($request['RestoreDefaults'])) {
        Module::deleteAllOptions();
        Module::setAllOptionsDefaults();
    }

    if (!$breakSave) {
        LocalRedirect($APPLICATION->GetCurPage()
                . '?mid=' . urlencode($mid)
                . '&lang=' . urlencode(LANGUAGE_ID)
                . '&' . $tabControl->ActiveTabParam()
                . (isset($backUrl) ? '&back_url_settings=' . urlencode($backUrl) : '')
        );
    }
}

/**
 * View
 */
?>
<?php $tabControl->Begin(); ?>
<form method="post" name="<?= str_replace('.', '_', $module_id) ?>_settings"
      action="<?= htmlspecialcharsbx($APPLICATION->GetCurPage()) ?>?mid=<?= urlencode($mid) ?>&lang=<?= urlencode
      (LANGUAGE_ID) ?>">
    <?= bitrix_sessid_post() ?>
    <?php foreach ($tabs as $tab) {
        if (isset($tab['OPTIONS'])) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $tab['OPTIONS']);
        }
    }

    if (CMain::GetGroupRight($module_id) === "W") {
        $tabControl->BeginNextTab();
        require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php');
    }

    $tabControl->Buttons(); ?>
    <input type="submit" name="Save" value="<?= CUtil::JSEscape(Loc::getMessage("MAIN_SAVE")) ?>"
           title="<?= CUtil::JSEscape(Loc::getMessage("MAIN_OPT_APPLY_TITLE")) ?>" class="adm-btn-save">
    <input type="submit" name="Apply" value="<?= CUtil::JSEscape(Loc::getMessage("MAIN_APPLY")) ?>"
           title="<?= CUtil::JSEscape(Loc::getMessage("MAIN_OPT_APPLY_TITLE")) ?>">
    <input type="submit" name="RestoreDefaults" title="<?= CUtil::JSEscape(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")) ?>"
           OnClick="return confirm('<?= CUtil::JSEscape(Loc::getMessage('MODULE_RESTORE_DEFAULTS_CONFIRMATION_MSG'))
           ?>')"
           value="<?= CUtil::JSEscape(Loc::getMessage("MAIN_RESTORE_DEFAULTS")) ?>">
    <?php if ($backUrl <> '') { ?>
        <input
                type="button"
                name="Cancel"
                value="<?= CUtil::JSEscape(Loc::getMessage('MAIN_OPT_CANCEL')) ?>"
                title="<?= CUtil::JSEscape(Loc::getMessage('MAIN_OPT_CANCEL_TITLE')) ?>"
                onclick="window.location='<?= htmlspecialcharsbx(CUtil::addslashes($backUrl)) ?>'"/>
        <input type="hidden" name="back_url_settings" value="<?= htmlspecialcharsbx($backUrl) ?>"/>
    <?php }?>
    <?php $tabControl->End(); ?>
</form>
