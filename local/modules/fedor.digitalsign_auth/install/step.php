<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!check_bitrix_sessid()) {
    return;
}

global $APPLICATION;

if ($errorException = $APPLICATION->GetException()) {
    echo(CAdminMessage::ShowMessage($errorException->GetString()));
} else {
    echo(CAdminMessage::ShowNote(Loc::getMessage("FEDOR_CRYPTOPRO_STEP_BEFORE") . "fedor.digitalsign_auth" . Loc::getMessage("FEDOR_CRYPTOPRO_STEP_AFTER")));
}
?>

<form action="<?= htmlspecialcharsbx($APPLICATION->GetCurPage()) ?>">
    <input type="hidden" name="lang" value="<?= LANG ?>"/>
    <input type="submit" value="<?= Loc::getMessage("FEDOR_CRYPTOPRO_STEP_SUBMIT_BACK") ?>">
</form>