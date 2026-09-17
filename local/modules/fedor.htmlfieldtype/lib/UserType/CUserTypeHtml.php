<?php

namespace Fedor\Htmlfieldtype\UserType;

use Bitrix\Main\Loader;
use CFileMan;

class CUserTypeHtml
{
    public static function GetUserTypeDescription(): array
    {
        return array(
            "USER_TYPE_ID" => 'fedor_htmlfieldtype',
            "CLASS_NAME" => __CLASS__,
            "DESCRIPTION" => 'HTML (fedor.htmlfieldtype)',
            "BASE_TYPE" => \CUserTypeManager::BASE_TYPE_STRING,
            "EDIT_CALLBACK" => [__CLASS__, 'GetPublicEdit'],
            "VIEW_CALLBACK" => [__CLASS__, 'GetPublicView'],
        );
    }

    public static function GetDBColumnType(): string
    {
        return "text";
    }

    public function getSettingsHtml($userField, $additionalParameters, $varsFromForm): string
    {
        return
            '<div class="adm-info-message-wrap" align="center">' .
            '<div class="adm-info-message">Примечание: данный тип поля пока что не поддерживает редактирование</div>' .
            '</div>';
    }

    public static function GetPublicView($arUserField, $arAdditionalParameters = array())
    {
		echo $arUserField['VALUE'];
		$html = ob_get_clean();
        return "<div class='ui-entity-editor-content-block-inner-html'>$html</div>";
    }

    public static function GetPublicEdit($arUserField, $arAdditionalParameters = array())
    {
        Loader::includeModule("fileman");
		ob_start();
        CFileMan::AddHTMLEditorFrame(
                $arUserField['FIELD_NAME'],
                $arUserField['VALUE'],
                $arUserField['FIELD_NAME'] . '_TYPE',
                'text',
                array(
                        'height' => 450,
                        'width' => '100%'
                )
        );
        ?>
            <style>
                #bx-html-editor-<?=$arUserField['FIELD_NAME']?> {
                    min-width: 100% !important;
                }
                #bx-html-editor-area-cnt-<?=$arUserField['FIELD_NAME']?> > * {
                    width: 100% !important;
                    max-width: 100% !important;
                }
                #bx-html-editor-area-cnt-<?=$arUserField['FIELD_NAME']?> .bxhtmled-top-bar-cnt {
                    width: 100% !important;
                    max-width: 100% !important;
                }
            </style>
        <?php
		$html = ob_get_clean();
        return $html;
    }
}