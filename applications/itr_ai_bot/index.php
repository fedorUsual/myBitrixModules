<?php
require_once (__DIR__.'/crestcurrent.php');
require_once (__DIR__.'/settings.php');

$result = CRestCurrent::call('user.current');
$userId = $result['result']['ID'];
$email  = $result['result']['EMAIL'];
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
                    src="https://itr-ai.ru/?userId=<?=$userId?>&email=<?=$email?>"
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin"
                    sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
                    title="AI ассистент"
            ></iframe>
        </div>

        <script>
            BX24.init(function () {
                BX24.fitWindow();
                window.addEventListener('resize', function () {
                    BX24.fitWindow();
                });
            });
        </script>
    </body>
</html>