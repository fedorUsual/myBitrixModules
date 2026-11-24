<?php
require_once (__DIR__.'/crest.php');
require_once (__DIR__.'/settings.php');

$result = CRest::installApp();
$title = 'AI ассистент';
$handler = AI_ASSIST_URL;
$data = [
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => ITR_APPLICATION_AI_PLACEMENT,
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => LEFT_MENU_AI_PLACEMENT,
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => TASK_VIEW_AI_PLACEMENT,
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => ITR_PROJECTS_AI_PLACEMENT,
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => COMPANY_AI_PLACEMENT,
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
];
CRest::callBatch($data);
if($result['rest_only'] === false):?>
	<head>
		<script src="//api.bitrix24.com/api/v1/"></script>
		<?php if($result['install'] == true):?>
			<script>
				BX24.init(function(){
					BX24.installFinish();
				});
			</script>
		<?php endif;?>
	</head>
	<body>
		<?php if($result['install'] == true):?>
			installation has been finished
		<?php else:?>
			installation error
		<?php endif;?>
	</body>
<?php endif;