<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php
if (!empty($arResult)): ?>
    <div class="main-menu-page cards">
    <?php $previousLevel = 0;
foreach ($arResult

    as $arItem): ?>
    <?php if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
        <?= str_repeat('<a class="card__list-item card__list-item--more" href="#">Еще <i class="bi bi-arrow-right icon-arrow"></i></a></div></div></div>',
            ($previousLevel - 1)); ?>
    <?php endif ?>

    <?php if ($arItem["IS_PARENT"]): ?>
    <div class="cards__item">
    <div class="card">
    <a href="<?= $arItem["LINK"] ?>" class="card__title"> <?= $arItem["TEXT"] ?></a>
    <div class="card__list">
    <?php else: ?>
        <?php if ($arItem["DEPTH_LEVEL"] == 1): ?>
            <div class="cards__item">
                <div class="card">
                    <a href="<?= $arItem["LINK"] ?>" class="card__title"> <?= $arItem["TEXT"] ?></a>
                </div>
            </div>
        <?php else: ?>
            <a class="card__list-item" href="<?= $arItem["LINK"] ?>"> <?= $arItem["TEXT"] ?></a>
        <?php endif; ?>
    <?php endif; ?>

    <?php $previousLevel = $arItem["DEPTH_LEVEL"]; ?>
<?php endforeach; ?>

    <?php if ($previousLevel > 1)://close last item tags?>
        <?= str_repeat('<a class="card__list-item card__list-item--more" href="#">Еще <i class="bi bi-arrow-right icon-arrow"></i></a></div></div></div>',
            ($previousLevel - 1)); ?>
    <?php endif ?>
    </div>
<?php endif ?>