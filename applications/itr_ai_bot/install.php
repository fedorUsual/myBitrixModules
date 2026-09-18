<?php
require_once (__DIR__.'/crest.php');
$result = CRest::installApp();
$title = 'AI ассистент';
$handler = getenv('AI_ASSIST_URL');
$data = [
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => getenv('ITR_APPLICATION_AI_PLACEMENT'),
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => getenv('LEFT_MENU_AI_PLACEMENT'),
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => getenv('TASK_VIEW_AI_PLACEMENT'),
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => getenv('ITR_PROJECTS_AI_PLACEMENT'),
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => getenv('COMPANY_AI_PLACEMENT'),
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