<?php

namespace Tender\Bot\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Crm\Service\Container;

/**
 * Основной контроллер Tender.Bot
 * Совмещает логику отображения и обработки событий CRM
 */
class AppController extends Controller
{
    /**
     * Карта стадий смарт-процесса
     */
    private static array $stageToTopic = [
        'DT1354_1:NEW'         => null,
        'DT1354_1:PREPARATION' => 12,
        'DT1354_1:UC_O9S4Z2'   => 15,
        'DT1354_1:SUCCESS'     => 20,
    ];

    /**
     * Стандартный Action для вывода интерфейса
     * URL: /api/faleev.tender-bot/view/
     */
    public function renderAction()
    {
        return "Интерфейс управления тендерами (AI Bot) инициализирован.";
    }

    /**
     * Бывший Handler::onEpilog
     * Подключает JS/CSS в карточку CRM
     */
    public static function onEpilog(): void
    {
      if (str_contains($_SERVER['REQUEST_URI'], '/crm/type/1354/details/')) {
        // Подгружаем ядро редактора
        \Bitrix\Main\UI\Extension::load(['html_editor', 'ui.design-tokens']);
        
        $asset = \Bitrix\Main\Page\Asset::getInstance();
        $asset->addJs('/local/modules/faleev.tender.bot/assets/js/editor_fix.js');
      }
    }

    /**
     * Логика уведомлений (бывший Telegram::send + Handler::onStageChange)
     */
    public static function onStageChange(int $tenderId, string $stageId): void
    {
        if (!Loader::includeModule('crm')) return;

        $factory = Container::getInstance()->getFactory(1354);
        $item = $factory ? $factory->getItem($tenderId) : null;
        
        if ($item) {
            $topicId = self::$stageToTopic[$stageId] ?? null;
            $msg = "🚀 <b>Тендер обновлен</b>\n";
            $msg .= "Название: " . $item->getTitle() . "\n";
            $msg .= "Ссылка: https://" . $_SERVER['HTTP_HOST'] . "/crm/type/1354/details/{$tenderId}/";

            // Вызываем отправку (теперь это может быть приватный метод этого же класса или AjaxController)
            self::sendToTelegram($msg, $topicId);
        }
    }

    /**
     * Инкапсулированная отправка в Telegram
     */
    private static function sendToTelegram(string $message, ?int $topicId = null): void
    {
        // Сюда переезжает код из старого telegram.php (curl к API Telegram)
        $botToken = "ВАШ_ТОКЕН";
        $chatId = "ВАШ_ЧАТ";
        // ... curl logic ...
    }
}

