<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

/**
 * @var $arResult array
 * @var $arParams array
 * @var $this CBitrixComponentTemplate
 */

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

global $APPLICATION;

$assets = Asset::getInstance();

Loc::loadMessages(__FILE__);

CJSCore::init();
?>
<div class="authorization-wrap text-center py-4 px-4 px-md-5 mx-auto">
    <h2><?=Loc::getMessage("AUTH_TITLE")?></h2>
    <h5><?=Loc::getMessage("AUTH_PLEASE_AUTH")?></h5>

    <div class="authorization-icon-wrap py-4">
        <div class="authorization-icon">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 14.5V16.5M7 10.0288C7.47142 10 8.05259 10 8.8 10H15.2C15.9474 10 16.5286 10 17 10.0288M7 10.0288C6.41168 10.0647 5.99429 10.1455 5.63803 10.327C5.07354 10.6146 4.6146 11.0735 4.32698 11.638C4 12.2798 4 13.1198 4 14.8V16.2C4 17.8802 4 18.7202 4.32698 19.362C4.6146 19.9265 5.07354 20.3854 5.63803 20.673C6.27976 21 7.11984 21 8.8 21H15.2C16.8802 21 17.7202 21 18.362 20.673C18.9265 20.3854 19.3854 19.9265 19.673 19.362C20 18.7202 20 17.8802 20 16.2V14.8C20 13.1198 20 12.2798 19.673 11.638C19.3854 11.0735 18.9265 10.6146 18.362 10.327C18.0057 10.1455 17.5883 10.0647 17 10.0288M7 10.0288V8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8V10.0288" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>
    <div id="loadErrors" class=""></div>
    <form id="formAuthentication" method="post" target="_top" action="<?=htmlspecialcharsbx($arResult["AUTH_URL"])?>"
          class="authorization-form">
        <input type="hidden" name="SIGN" value="" />
        <input type="hidden" name="CERT_Thumbprint" value="" />
        <div class="form-row">
            <div class="form-group col-12 text-left">
                <label for="inlineFormCustomSelect"><?=Loc::getMessage("AUTH_LOGIN")?></label>
                <select class="custom-select" id="loadCertificate">
                    <option value="" selected><?=Loc::getMessage("AUTH_NOTE_ZERO_OPTION")?></option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn w-100 text-white js-btn-send"><?=Loc::getMessage("AUTH_BTN_NAME")?></button>
        <div class="auth-user-consent">
        <?php
        $APPLICATION->IncludeComponent(
            "bitrix:main.userconsent.request",
            "",
            [
                "ID" => $arResult['AGREEMENT_ID'],
                "IS_CHECKED" => "N",
                "IS_LOADED" => "Y",
                "REG_FIELDS" => ["NAME", "PHONE", "EMAIL"],
                "INPUT_NAME" => "USER_CONSENT_INPUT_NAME",
                "REPLACE" => [
                        'button_caption' => 'Отправить',
                        'fields' => ['Имя', 'Телефон', 'Email']
                ],
            ]
        ); ?>
        </div>
    </form>
    <p class="my-4"><a href="/"><?=Loc::getMessage("AUTH_POLICY")?></a></p>
    <p><?=Loc::getMessage("AUTH_NOTE")?></p>
</div>
<?php
    $arResult['JS_PARAMS'] = [
        'UID' => base64_encode($arResult["UID"]),
        'BACK_URL' => $arResult["AUTH_URL"],
    ];
?>
<script>
    BX.ready(function () {
        new CryptoproAuthAuthorize(<?= CUtil::PhpToJSObject($arResult['JS_PARAMS']) ?>);
    });
</script>