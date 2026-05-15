<?php

class LabelWithPlaceholdersSpoiler
{
    private array $placeholders;
    private string $langCodePrefix;

    public function __construct($placeholdersArray, $langCodePrefix)
    {
        $this->placeholders = $placeholdersArray;
        $this->langCodePrefix = $langCodePrefix;
    }

    public function create($optionName): string
    {
        return GetMessage($this->langCodePrefix . $optionName) . '<br><br>' .
            $this->getPlaceholdersSpoiler($this->placeholders[$optionName]);
    }

    private function getPlaceholdersSpoiler($arPlaceholdersList)
    {
        ob_start(); ?>
        <details style="text-align: left;">
            <summary style="text-align: right;"><?= GetMessage('NORBIT_AVAILABLE_PLACEHOLDERS') ?></summary>
            <br>
            <?php
            foreach ($arPlaceholdersList as $placeholder): ?>
                #<?= $placeholder ?># - <?= GetMessage($this->langCodePrefix . 'PLACEHOLDER_' . $placeholder) ?><br>
            <?php endforeach; ?>
        </details>
        <?php return ob_get_clean();
    }
}