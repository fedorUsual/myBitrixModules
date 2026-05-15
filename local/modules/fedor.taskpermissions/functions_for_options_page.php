<?php
//этот файл можно (и даже нужно) удалить, если он находится не в главном local модуле портала (обычно это norbit.main)
//если находится в главном, то не удалять ни в коем случае (на него должны ссылаться остальные)

if (!function_exists('__DrawAddFieldsArea')) {
    function __DrawAddFieldsArea($tabID)
    {
        ?>
        <tr>
            <td>
                <table style="width:100%">
                    <tr>
                        <td><input type="text" name="new_field_block[<?= $tabID ?>][]"
                                   placeholder="<?= GetMessage('NORBIT_NEW_FIELD_BLOCK') ?>" style="width:90%"></td>
                        <td style="text-align:right;"><input type="text" name="new_field_title[<?= $tabID ?>][]"
                                                             placeholder="<?= GetMessage('NORBIT_NEW_FIELD_TITLE') ?>" style="width:90%">
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table style="width:100%">
                    <tr>
                        <td><input type="text" name="new_field_name[<?= $tabID ?>][]" placeholder="<?= GetMessage('NORBIT_NEW_FIELD_NAME') ?>"
                                   style="width:90%"></td>
                        <td style="text-align:right;"><input type="text" name="new_field_value[<?= $tabID ?>][]"
                                                             placeholder="<?= GetMessage('NORBIT_NEW_FIELD_VALUE') ?>" style="width:90%"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <script type="text/javascript">
					function settingsAddRow(a) {
						var row = jsUtils.FindParentObject(a, "tr");
						var tbl = row.parentNode;

						var tableRow = tbl.rows[row.rowIndex - 1].cloneNode(true);
						tbl.insertBefore(tableRow, row);
					}
                </script>
                <a href="javascript:void(0)" onclick="settingsAddRow(this)" hidefocus="true" class="bx-action-href">Добавить
                    строку</a>
            </td>
        </tr>
        <?
    }
}
if(!function_exists('CreateNote')) {
    function CreateNote($text, $beginNoteArParams = '')
    {
        return BeginNote($beginNoteArParams) . $text . EndNote();
    }
}

if (!function_exists('GetHtmlEditor')){
    function GetHtmlEditor($inputName, $moduleId)
    {
        if (!CModule::IncludeModule("fileman")) {
            return false;
        }
        $Editor = new CHTMLEditor;
        ob_start();
        $Editor->Show(
            array (
                'inputName' => $inputName,
                'siteId' => 's1',
                'width' => '100%',
                'height' => 300,
                'content' => \Bitrix\Main\Config\Option::get($moduleId, $inputName),
                'bAllowPhp' => false,
                'limitPhpAccess' => false,
                'showTaskbars' => true,
                'showSnippets' => false,
                'display' => true,
//                'askBeforeUnloadPage' => false,
                'componentFilter' => false,
                'setFocusAfterShow' => false,
                'relPath' => '/',
                'templateId' => '.default',
            )
        );
        return ob_get_clean();
    }
}