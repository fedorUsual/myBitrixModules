<?php
require_once (__DIR__.'/crestcurrent.php');
require_once (__DIR__.'/settings.php');

    //$result = CRest::call('user.current');
$result = CRestCurrent::call('user.current');

$sharedSecret = defined('C_REST_CLIENT_SECRET') ? C_REST_CLIENT_SECRET : '';

$userId = $result['result']['ID'];
$email  = $result['result']['EMAIL'];
// -------------------------------------------------------------------------

$timestamp = time();

$payload = $userId . '|' . $email . '|' . $timestamp;
$signature = hash_hmac('sha256', $payload, $sharedSecret);
require_once('crest.php');
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>AI ассистент</title>
        <script src="//api.bitrix24.com/api/v1/"></script>
        <style>
            html, body { margin: 0; padding: 0; height: 100%; }
            .itrai-embed-wrap { width: 100%; height: 100vh; }
            .itrai-embed-wrap iframe { width: 100%; height: 100%; border: 0; display: block; }
        </style>
    </head>
    <body>
        <div class="itrai-embed-wrap">
            <iframe
                    id="itraiFrame"
                    name="itraiFrame"
                    src="about:blank"
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin"
                    sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
                    title="ITR AI"
            ></iframe>
        </div>
         <form id="itraiPostForm"
              method="POST"
              action="https://itr-ai.ru"
              target="itraiFrame"
              style="display:none;">
            <input type="hidden" name="userId" value="<?= htmlspecialchars((string)$userId, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="timestamp" value="<?= (int)$timestamp ?>">
            <input type="hidden" name="signature" value="<?= htmlspecialchars($signature, ENT_QUOTES, 'UTF-8') ?>">
        </form>

        <script>
            BX24.init(function () {
                // Отправляем подписанные данные во фрейм POST-ом
                document.getElementById('itraiPostForm').submit();

                BX24.fitWindow();
                window.addEventListener('resize', function () {
                    BX24.fitWindow();
                });
            });
        </script>
    </body>
</html>