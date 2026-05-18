<?php
namespace Tender\Bot\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Crm\Service\Container;

class AjaxController extends Controller
{   
    private const BOT_TOKEN = 'ВАШ_ТОКЕН_BOT_FATHER';
    private const CHAT_ID = '-100XXXXXXXXXX'; // ID вашей группы с темами

    public static function send($message, $topicId = null) {
        $url = "https://telegram.org" . self::BOT_TOKEN . "/sendMessage";
        $params = [
            'chat_id' => self::CHAT_ID,
            'text' => $message,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => false
        ];
        
        if ($topicId) {
            $params['message_thread_id'] = $topicId;
        }

        $httpClient = new \Bitrix\Main\Web\HttpClient();
        $httpClient->post($url, $params);
    }
}
