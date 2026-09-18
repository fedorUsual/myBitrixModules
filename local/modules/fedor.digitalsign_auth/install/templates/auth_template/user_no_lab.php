<?php
use  Bitrix\Main\Localization\Loc;

global $APPLICATION;
$APPLICATION->SetTitle('');
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
?>

<div class="flex-fill">

    <div class="text-center">
        <img src="<?=SITE_TEMPLATE_PATH . '/assets/images/error_bg.svg'?>" class="img-fluid" height="230" width="450" alt="">
    </div>
    <h5 class="w-md-25 mx-md-auto text-center"><?=Loc::getMessage('SERCONS_USER_NO_LABS');?></h5>
</div>

