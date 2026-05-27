<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

// Подключаем стандартные стили Битрикса и расширения (например, для кнопок и шрифтов)
Extension::load(['ui.design-tokens', 'ui.fonts.opensans', 'ui.buttons', 'ui.alerts']);

/**
 * Здесь будет подключаться ваш скомпилированный JS (React/Vue)
 * Например: Asset::getInstance()->addJs('/local/modules/faleev.tender/frontend/dist/app.js');
 */
?>

<div class="tender-app-container" style="padding: 20px; background: #fff; border-radius: 5px;">
    <div class="ui-alert ui-alert-success">
        <span class="ui-alert-message">
            <strong>Модуль Tender активен!</strong> Интерфейс загружен из <code>local/modules/tender/frontend/index.php</code>
        </span>
    </div>

    <div style="margin-top: 20px;">
        <h2>Панель управления тендерами</h2>
        <p>Текущий статус системы: <span class="ui-label ui-label-fill ui-label-success">Online</span></p>
        
        <div style="margin-top: 30px;">
            <button class="ui-btn ui-btn-primary" onclick="alert('Здесь будет запуск парсинга')">
                Обновить данные из ИИ
            </button>
            <a href="/bitrix/admin/tender_logs.php" class="ui-btn ui-btn-link">Посмотреть логи</a>
        </div>
    </div>
</div>

<script>
    // Здесь можно инициализировать ваше приложение
    console.log('Tender Frontend Initialized');
</script>
