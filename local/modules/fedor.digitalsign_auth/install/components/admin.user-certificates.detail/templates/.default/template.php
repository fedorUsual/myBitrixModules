<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UI\Extension;

Extension::load("ui.dialogs.messagebox");

/** @var CMain $APPLICATION */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

Asset::getInstance()->addCss('/bitrix/panel/main/admin.css');
Asset::getInstance()->addCss('/bitrix/themes/.default/admin.css');

$item = $arResult['ITEM'];
?>
<div class="adm-detail-block" id="tabControl_layout">
    <div class="adm-detail-content-wrap">
        <div class="adm-detail-content" id="edit1">
            <div class="adm-detail-content-item-block">
                <table class="adm-detail-content-table edit-table" id="edit1_edit_table">
                    <tbody>
                    <?php
                    foreach ($item as $fieldName => $fieldValue) {
                        ?><tr><?php
                        if ($fieldValue instanceof DateTime) {?>
                            <td class="adm-detail-content-cell-l">
                                <?= $fieldName ?>:
                            </td>
                            <td class="adm-detail-content-cell-r">
                                <input type="text" disabled value="<?=$fieldValue->format('d.m.Y H:i:s')?>" autocomplete="off">
                            </td>
                            <?php
                            continue;
                        }?>
                            <td class="adm-detail-content-cell-l">
                                <?= $fieldName ?>:
                            </td>
                            <td class="adm-detail-content-cell-r">
                                <input type="text" disabled value="<?=htmlspecialcharsbx($fieldValue)?>"
                                       autocomplete="off">
                            </td>
                        <?php
                        ?></tr><?php
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    BX.ready(function () {
        new UserCertificatesDetail(<?= CUtil::PhpToJSObject([
            'signedParameters' => $this->getComponent()->getSignedParameters(),
            'componentName' => $this->getComponent()->getName(),
        ]) ?>);
    });
</script>
