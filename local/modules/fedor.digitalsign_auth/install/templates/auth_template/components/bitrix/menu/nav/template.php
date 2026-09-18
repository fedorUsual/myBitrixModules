<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php if (!empty($arResult)): ?>
    <ul class="nav-list">
    <?php $previousLevel = 0;
foreach ($arResult as $arItem):
    $skipItem = false;
    if (isset($arParams["MAX_LEVEL"]) && $arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) {
        $skipItem = true;
    }

    if ($skipItem) {
        continue;
    }

    if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
        <?= str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
    <?php endif; ?>

    <?php if (isset($arItem["IS_PARENT"]) && $arItem["IS_PARENT"]): ?>
    <li>
    <a href="<?= $arItem["LINK"] ?>" class="nav-item <?php if (isset($arItem["SELECTED"]) && $arItem["SELECTED"]): ?>active<?php endif ?>">
                        <span class="short">
                            <?php if (!empty($arItem["PARAMS"]["ICON"])): ?>
                                <i class="<?= $arItem["PARAMS"]["ICON"] ?>"></i>
                            <?php else: ?>
                                <?= mb_substr($arItem["TEXT"], 0, 2) ?>
                            <?php endif; ?>
                        </span>
        <span class="full">
                            <?php if (!empty($arItem["PARAMS"]["ICON"])): ?>
                                <i class="<?= $arItem["PARAMS"]["ICON"] ?>"></i>
                            <?php endif; ?>
            <?= $arItem["TEXT"] ?>
                        </span>
    </a>
    <ul class="nav-sublist <?= (!empty($arItem["PARAMS"]["MUST_OPEN_SUBMENU"]) && $arItem["PARAMS"]["MUST_OPEN_SUBMENU"] === "Y") ? 'must-open' : ''?>">
    <?php else: ?>
        <?php if ($arItem["DEPTH_LEVEL"] == 1): ?>
            <li>
                <a href="<?= $arItem["LINK"] ?>" class="nav-item <?php if (isset($arItem["SELECTED"]) && $arItem["SELECTED"]): ?>active<?php endif ?>">
                            <span class="short">
                                <?php if (!empty($arItem["PARAMS"]["ICON"])): ?>
                                    <i class="<?= $arItem["PARAMS"]["ICON"] ?>"></i>
                                <?php else: ?>
                                    <?= mb_substr($arItem["TEXT"], 0, 2) ?>
                                <?php endif; ?>
                            </span>
                    <span class="full">
                                <?php if (!empty($arItem["PARAMS"]["ICON"])): ?>
                                    <i class="<?= $arItem["PARAMS"]["ICON"] ?>"></i>
                                <?php endif; ?>
                        <?= $arItem["TEXT"] ?>
                            </span>
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="<?= $arItem["LINK"] ?>" class="nav-subitem <?php if (isset($arItem["SELECTED"]) && $arItem["SELECTED"]): ?>active<?php endif ?>">
                            <span class="full">
                                <?= $arItem["TEXT"] ?>
                            </span>
                </a>
            </li>
        <?php endif; ?>
    <?php endif; ?>

    <?php $previousLevel = $arItem["DEPTH_LEVEL"]; ?>
<?php endforeach; ?>

    <?php if ($previousLevel > 1): ?>
        <?= str_repeat("</ul></li>", ($previousLevel - 1)); ?>
    <?php endif; ?>
    </ul>
<?php endif; ?>