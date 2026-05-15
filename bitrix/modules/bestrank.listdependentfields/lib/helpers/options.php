<?php


namespace Bestrank\ListDependentFields\Helper;


use Bitrix\Main\Application,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Loader,
    Bestrank\ListDependentFields\Iblock;
use Bitrix\Main\Web\Json;

class Options
{
    const moduleId = 'bestrank.listdependentfields';

    public static function getModuleId()
    {
        return self::moduleId;
    }


    public static function getIblockList(array $filter = [])
    {
        $arIblockLists = Iblock::getLists($filter);
        return $arIblockLists;
    }

    public static function getParam($code, $default = null)
    {
        return trim(Option::get(self::getModuleId(), $code, $default ?? ''));
    }

    public static function deleteParam($filter)
    {
        Option::delete(self::getModuleId(), $filter);
    }

    public static function getAllParams()
    {
        $moduleId = self::getModuleId();
        $options = [];
        $con = \Bitrix\Main\Application::getConnection();
        $sqlHelper = $con->getSqlHelper();
        $query = "
				SELECT NAME, VALUE
				FROM b_option
				WHERE MODULE_ID = '{$sqlHelper->forSql($moduleId)}' AND NAME LIKE 'BR_LDF_SETTINGS_IB_%'
			";

        $res = $con->query($query);
        while ($ar = $res->fetch()) {
            $name = str_replace('BR_LDF_SETTINGS_IB_', '', $ar["NAME"]);
            try {
                $value = Json::decode($ar["VALUE"]);
            } catch (\Exception $e) {
                $value = [];
            }
            $options[$name] = $value;
        }

        return $options;
    }

    public static function prepareSettingsOption($settings, $fieldCodes)
    {
        $return = [];
        $arrTypes = ['bind', 'regex', 'range', 'required', 'length', 'fill'];
        foreach ($settings as $settingId => $settingVal) {
            if (in_array($settingId, $arrTypes)) {
                $newSettingVal = [];
                foreach ($settingVal as $fieldId => $fieldNameArr) {
                    if($settingId == 'fill'){
                        if (in_array($fieldNameArr, $fieldCodes)) {
                            $newSettingVal[$fieldId] = $fieldNameArr;
                        }
                    } else {
                        foreach($fieldNameArr as $fieldNameArrKey => $fieldName){
                            if (in_array($fieldName, $fieldCodes)) {
                                $newSettingVal[$fieldId][] = $fieldName;
                            }
                        }
                    }
                }
                $return[$settingId] = $newSettingVal;
            } else {
                $return[$settingId] = $settingVal;
            }
        }
        return $return;
    }

    public static function getFieldCodes($iblockId)
    {
        $return = [];
        $fields = \Bestrank\ListDependentFields\Iblock::getIblockFields($iblockId, true);
        foreach ($fields as $fieldId => $fieldName) {
            if (substr($fieldId, 0, 9) == 'PROPERTY_') {
                $return[str_replace('PROPERTY_', '', $fieldId)] = $fieldId;
            } else {
                $return[$fieldId] = $fieldId;
            }
        }
        return $return;
    }

    public static function delOptionByPropId($code, $propId)
    {
        if ($propId && $code) {
            if (substr($propId, 0, 9) == 'PROPERTY_') {
                $propCode = $propId;
                $propId = str_replace('PROPERTY_', '', $propId);
            } else {
                $propCode = 'PROPERTY_' . $propId;
            }
            $iblockSettings = trim(Option::get(self::getModuleId(), $code, null));
            try {
                $iblockSettings = Json::decode($iblockSettings);
            } catch (\Exception $e) {
                $iblockSettings = [];
            }
            if (isset($iblockSettings[$propId])) {
                unset($iblockSettings[$propId]);
            }
            $arrTypes = ['bind', 'regex', 'range', 'required', 'length', 'fill'];
            foreach ($iblockSettings as $settingsId => $settings) {
                foreach ($arrTypes as $type) {
                    if ($settings[$type]){
                        if($type == 'fill'){
                                $iblockSettings[$settingsId][$type] = array_diff($settings[$type], [$propCode]);
                        } else {
                            foreach ($settings[$type] as $settingsTypeKey => $settingsType) {
                                $iblockSettings[$settingsId][$type][$settingsTypeKey] = array_diff($settingsType, [$propCode]);
                            }
                        }

                    }

                }
            }
            $iblockSettings = Json::encode($iblockSettings);
            self::setParam($code, $iblockSettings);
        }

    }

    public static function setParam($code, $value, $default = null)
    {
        Option::set(self::getModuleId(), $code, $value, $default);
    }

    public static function includeModules()
    {
        $arModules = [
            'iblock',
            'lists'
        ];

        foreach ($arModules as $moduleName) {
            Loader::includeModule($moduleName);
        }
    }

    /**
     * �������� ���� � ������
     * @param bool $absolute
     * @return string
     */
    public static function getModuleDir(bool $absolute = false): string
    {
        if ($absolute)
            return str_replace('lib/helpers', "", __DIR__);

        return str_replace([Application::getDocumentRoot(), 'lib/helpers'], "", __DIR__);
    }

    public static function settingsDrawList($arParams)
    {
        foreach ($arParams as $Option) {
            self::settingsDrawRow($Option);
        }
    }

    public static function settingsDrawRow($Option)
    {
        $arControllerOption = \CControllerClient::GetInstalledOptions(self::moduleId);
        if ($Option === null) {
            return;
        }

        if (!is_array($Option)):
            ?>
            <tr class="heading">
                <td colspan="2"><?= $Option ?></td>
            </tr>
        <?
        elseif (isset($Option["note"])):
            ?>
            <tr>
                <td colspan="2" align="center">
                    <? echo BeginNote('align="center"'); ?>
                    <?= $Option["note"] ?>
                    <? echo EndNote(); ?>
                </td>
            </tr>
        <?
        else:
            $isChoiceSites = array_key_exists(6, $Option) && $Option[6] == "Y" ? true : false;
            $listSite = array();
            $listSiteValue = array();
            if ($Option[0] != "") {
                if ($isChoiceSites) {
                    $queryObject = \Bitrix\Main\SiteTable::getList(array(
                        "select" => array("LID", "NAME"),
                        "filter" => array(),
                        "order" => array("SORT" => "ASC"),
                    ));
                    $listSite[""] = GetMessage("MAIN_ADMIN_SITE_DEFAULT_VALUE_SELECT");
                    $listSite["all"] = GetMessage("MAIN_ADMIN_SITE_ALL_SELECT");
                    while ($site = $queryObject->fetch()) {
                        $listSite[$site["LID"]] = $site["NAME"];
                        $val = \COption::GetOptionString(self::moduleId, $Option[0], $Option[2], $site["LID"], true);
                        if ($val)
                            $listSiteValue[$Option[0] . "_" . $site["LID"]] = $val;
                    }
                    $val = "";
                    if (empty($listSiteValue)) {
                        $value = \COption::GetOptionString(self::moduleId, $Option[0], $Option[2]);
                        if ($value)
                            $listSiteValue = array($Option[0] . "_all" => $value);
                        else
                            $listSiteValue[$Option[0]] = "";
                    }
                } else {
                    $val = \COption::GetOptionString(self::moduleId, $Option[0], $Option[2]);
                }
            } else {
                $val = $Option[2];
            }
            if ($isChoiceSites):?>
                <tr>
                    <td colspan="2" style="text-align: center!important;">
                        <label><?= $Option[1] ?></label>
                    </td>
                </tr>
            <? endif; ?>
            <? if ($isChoiceSites):
            foreach ($listSiteValue as $fieldName => $fieldValue):?>
                <tr>
                    <?
                    $siteValue = str_replace($Option[0] . "_", "", $fieldName);
                    self::renderLabel($Option, $listSite, $siteValue);
                    self::renderInput($Option, $arControllerOption, $fieldName, $fieldValue);
                    ?>
                </tr>
            <? endforeach; ?>
        <? else: ?>
            <tr>
                <?
                self::renderLabel($Option, $listSite);
                self::renderInput($Option, $arControllerOption, $Option[0], $val);
                ?>
            </tr>
        <? endif; ?>
            <? if ($isChoiceSites): ?>
            <tr>
                <td width="50%">
                    <a href="javascript:void(0)" onclick="addSiteSelector(this)" class="bx-action-href">
                        <?= GetMessage("MAIN_ADMIN_ADD_SITE_SELECTOR") ?>
                    </a>
                </td>
                <td width="50%"></td>
            </tr>
        <? endif; ?>
        <?
        endif;
    }

    public static function renderLabel($Option, array $listSite, $siteValue = "")
    {
        $type = $Option[3];
        $sup_text = array_key_exists(5, $Option) ? $Option[5] : '';
        $isChoiceSites = array_key_exists(6, $Option) && $Option[6] == "Y" ? true : false;
        ?>
        <? if ($isChoiceSites): ?>
        <script type="text/javascript">
            function changeSite(el, fieldName) {
                var tr = jsUtils.FindParentObject(el, "tr");
                var sel = null, tagNames = ["select", "input", "textarea"];
                for (var i = 0; i < tagNames.length; i++) {
                    sel = jsUtils.FindChildObject(tr.cells[1], tagNames[i]);
                    if (sel) {
                        sel.name = fieldName + "_" + el.value;
                        break;
                    }

                }
            }

            function addSiteSelector(a) {
                var row = jsUtils.FindParentObject(a, "tr");
                var tbl = row.parentNode;
                var tableRow = tbl.rows[row.rowIndex - 1].cloneNode(true);
                tbl.insertBefore(tableRow, row);
                var sel = jsUtils.FindChildObject(tableRow.cells[0], "select");
                sel.name = "";
                sel.selectedIndex = 0;
                sel = jsUtils.FindChildObject(tableRow.cells[1], "select");
                sel.name = "";
                sel.selectedIndex = 0;
            }
        </script>
        <td width="50%">
            <select onchange="changeSite(this, '<?= htmlspecialcharsbx($Option[0]) ?>')">
                <? foreach ($listSite as $lid => $siteName): ?>
                    <option <? if ($siteValue == $lid) echo "selected"; ?> value="<?= htmlspecialcharsbx($lid) ?>">
                        <?= htmlspecialcharsbx($siteName) ?>
                    </option>
                <? endforeach; ?>
            </select>
        </td>
    <? else: ?>
        <td<? if ($type[0] == "multiselectbox" || $type[0] == "textarea" || $type[0] == "statictext" ||
            $type[0] == "statichtml") echo ' class="adm-detail-valign-top"' ?> width="50%"><?
            if ($type[0] == "checkbox")
                echo "<label for='" . htmlspecialcharsbx($Option[0]) . "'>" . $Option[1] . "</label>";
            else
                echo $Option[1];
            if ($sup_text <> '') {
                ?><span class="required"><sup><?= $sup_text ?></sup></span><?
            }
            ?><a name="opt_<?= htmlspecialcharsbx($Option[0]) ?>"></a></td>
    <?endif;
    }

    public static function renderInput($Option, $arControllerOption, $fieldName, $val)
    {
        $type = $Option[3];
        $disabled = array_key_exists(4, $Option) && $Option[4] == 'Y' ? ' disabled' : '';
        ?>
        <td width="50%"><?
        if ($type[0] == "checkbox"):
            ?><input
            type="checkbox" <? if (isset($arControllerOption[$Option[0]])) echo ' disabled title="' . GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT") . '"'; ?>
            id="<? echo htmlspecialcharsbx($Option[0]) ?>" name="<?= htmlspecialcharsbx($fieldName) ?>"
            value="Y"<? if ($val == "Y") echo " checked"; ?><?= $disabled ?><? if ($type[2] <> '') echo " " . $type[2] ?>><?
        elseif ($type[0] == "text" || $type[0] == "password"):
            ?>
            <input type="<? echo $type[0] ?>"<? if (isset($arControllerOption[$Option[0]])) echo ' disabled title="' . GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT") . '"'; ?>
            size="<? echo $type[1] ?>" maxlength="255"
            value="<? echo htmlspecialcharsbx($val) ?>" name="<?= htmlspecialcharsbx($fieldName) ?>"<?= $disabled ?><?= ($type[0] == "password" || $type["noautocomplete"] ? ' autocomplete="new-password"' : '') ?>><?
        elseif ($type[0] == "selectbox"):
            $arr = $type[1];
            if (!is_array($arr))
                $arr = array();
            ?><select
            name="<?= htmlspecialcharsbx($fieldName) ?>" <? if (isset($arControllerOption[$Option[0]])) echo ' disabled title="' . GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT") . '"'; ?> <?= $disabled ?>><?
            foreach ($arr as $key => $v):
                ?>
                <option value="<? echo $key ?>"<? if ($val == $key) echo " selected" ?>><? echo htmlspecialcharsbx($v) ?></option><?
            endforeach;
            ?></select><?
        elseif ($type[0] == "multiselectbox"):
            $arr = $type[1];
            if (!is_array($arr))
                $arr = [];

            if (is_array($val))
                $arr_val = $val;
            else
                $arr_val = explode(",", $val);
            ?><select
            size="5" <? if (isset($arControllerOption[$Option[0]])) echo ' disabled title="' . GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT") . '"'; ?>
            multiple name="<?= htmlspecialcharsbx($fieldName) ?>[]"<?= $disabled ?>><?
            foreach ($arr as $key => $v):
                ?>
                <option value="<? echo $key ?>"<? if (in_array($key, $arr_val)) echo " selected" ?>><? echo htmlspecialcharsbx($v) ?></option><?
            endforeach;
            ?></select><?
        elseif ($type[0] == "textarea"):
            ?>
            <textarea <? if (isset($arControllerOption[$Option[0]])) echo ' disabled title="' . GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT") . '"'; ?>
            rows="<? echo $type[1] ?>"
            cols="<? echo $type[2] ?>" name="<?= htmlspecialcharsbx($fieldName) ?>"<?= $disabled ?>><? echo htmlspecialcharsbx($val) ?></textarea><?
        elseif ($type[0] == "statictext"):
            echo htmlspecialcharsbx($val);
        elseif ($type[0] == "statichtml"):
            echo $val;
        endif; ?>
        </td><?
    }

    public static function settingsSaveOptions($arOptions)
    {
        foreach ($arOptions as $arOption) {
            self::settingsSaveOption($arOption);
        }
    }

    public static function settingsSaveOption($arOption)
    {
        if (!is_array($arOption) || isset($arOption["note"]))
            return false;

        if ($arOption[3][0] == "statictext" || $arOption[3][0] == "statichtml")
            return false;

        $arControllerOption = \CControllerClient::GetInstalledOptions(self::moduleId);

        if (isset($arControllerOption[$arOption[0]]))
            return false;

        $name = $arOption[0];
        $isChoiceSites = array_key_exists(6, $arOption) && $arOption[6] == "Y" ? true : false;

        if ($isChoiceSites) {
            if (isset($_REQUEST[$name . "_all"]) && $_REQUEST[$name . "_all"] <> '')
                \COption::SetOptionString(self::moduleId, $name, $_REQUEST[$name . "_all"], $arOption[1]);
            else
                \COption::RemoveOption(self::moduleId, $name);
            $queryObject = \Bitrix\Main\SiteTable::getList(array(
                'select' => array('LID', 'NAME'),
                'filter' => array(),
                'order' => array('SORT' => 'ASC'),
            ));
            while ($site = $queryObject->fetch()) {
                if (isset($_REQUEST[$name . "_" . $site["LID"]]) && $_REQUEST[$name . "_" . $site["LID"]] <> '' &&
                    !isset($_REQUEST[$name . "_all"])) {
                    $val = $_REQUEST[$name . "_" . $site["LID"]];
                    if ($arOption[3][0] == "checkbox" && $val != "Y")
                        $val = "N";
                    if ($arOption[3][0] == "multiselectbox")
                        $val = @implode(",", $val);
                    \COption::SetOptionString(self::moduleId, $name, $val, $arOption[1], $site["LID"]);
                } else {
                    \COption::RemoveOption(self::moduleId, $name, $site["LID"]);
                }
            }
        } else {
            if (!isset($_REQUEST[$name])) {
                if ($arOption[3][0] <> 'checkbox' && $arOption[3][0] <> "multiselectbox") {
                    return false;
                }
            }

            $val = $_REQUEST[$name];

            if ($arOption[3][0] == "checkbox" && $val != "Y")
                $val = "N";
            if ($arOption[3][0] == "multiselectbox" && is_array($val))
                $val = @implode(",", $val);

            \COption::SetOptionString(self::moduleId, $name, $val, $arOption[1]);
        }

        return null;
    }
}