<?php
require_once (__DIR__.'/crestcurrent.php');
require_once (__DIR__.'/settings.php');

$result = CRestCurrent::call('user.current');
$userId = $result['result']['ID'];
$email  = $result['result']['EMAIL'];
$secret = SECRET;
$bridgeUrl = (string)(defined('ITR_AI_BRIDGE_URL') ?: 'https://itr-ai.ru/b24-login');

function b64url(string $bytes): string
{
    return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
}

$payload = b64url(
    json_encode(
        [
            'email'     => $email,
            'bitrix_id' => (int)$userId,
            'iat'       => time(),
            'nonce'     => bin2hex(random_bytes(16)),
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    )
);

$token = $payload . '.' . b64url(hash_hmac('sha256', $payload, $secret, true));

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
                    referrerpolicy="strict-origin-when-cross-origin"
                    sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
                    title="AI ассистент"
            ></iframe>
        </div>
        <form id="itraiPostForm" method="post" action="<?= htmlspecialchars($bridgeUrl, ENT_QUOTES, 'UTF-8') ?>"
              target="itraiFrame"
              style="display:none">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
        </form>

        <script>
            document.getElementById('itraiPostForm').submit();

            BX24.init(function () {
                BX24.fitWindow();
                window.addEventListener('resize', function () {
                    BX24.fitWindow();
                });
            });
        </script>
    </body>
</html>