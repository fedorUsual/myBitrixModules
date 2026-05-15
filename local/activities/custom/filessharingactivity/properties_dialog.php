<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<tr>
    <td align="right" width="40%"><?= GetMessage("BPTA1A_USER_TO_PERM") ?></td>
    <td width="60%">
        <input type="text" name="share_to_user" id="id_share_to_user" value="<?= htmlspecialchars($arCurrentValues["share_to_user"]) ?>" size="50">
        <input type="button" value="..." onclick="BPAShowSelector('id_share_to_user', 'user');">
    </td>
</tr>
<tr>
    <td align="right" width="40%"><?= GetMessage("BPTA1A_FOLDER_ID") ?>:</td>
    <td width="60%">
        <input type="text" name="folder_id" id="id_folder_id" value="<?= htmlspecialchars($arCurrentValues["folder_id"]) ?>" size="50">
        <input
                type="button" value="..."
                onclick="BPAShowSelector('id_folder_id', 'text', '');"
        >
    </td>
</tr>
<tr>
    <td align="right" width="40%"><?= GetMessage("BPTA1A_FILE_ID") ?>:</td>
    <td width="60%">
        <input type="text" name="file_id" id="id_file_id" value="<?= htmlspecialchars($arCurrentValues["file_id"]) ?>" size="50">
        <input
                type="button" value="..."
                onclick="BPAShowSelector('id_file_id', 'text', '');"
        >
    </td>
</tr>
<tr>
    <td align="right" width="40%"><?= GetMessage("BPTA1A_PERMS") ?>:</td>
    <td width="60%">
        <select name="rights_to_file">
			<?php
			foreach ($arTaskPermission as $key => $value)
			{?>
                <option value="<?= $key ?>"<?= $arCurrentValues["rights_to_file"] == $key ? " selected" : "" ?>>
                    <?= $value ?>
                </option><?php
			} ?>
        </select>
    </td>
</tr>