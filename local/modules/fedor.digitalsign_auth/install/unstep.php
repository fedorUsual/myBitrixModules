<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!check_bitrix_sessid()) {
    return;
}
global $APPLICATION;

echo(CAdminMessage::ShowNote(Loc::getMessage("FEDOR_CRYPTOPRO_UNSTEP_BEFORE") . " fedor.digitalsign_auth " . Loc::getMessage("FEDOR_CRYPTOPRO_UNSTEP_AFTER")));
?>

<form action="<?= htmlspecialcharsbx($APPLICATION->GetCurPage()) ?>">
    <input type="hidden" name="lang" value="<?= LANG ?>"/>
    <input type="submit" value="<?= Loc::getMessage("FEDOR_CRYPTOPRO_UNSTEP_SUBMIT_BACK") ?>">
</form>