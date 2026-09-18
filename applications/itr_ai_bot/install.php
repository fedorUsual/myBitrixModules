<?php
require_once (__DIR__.'/crest.php');
$result = CRest::installApp();
$title = 'AI ассистент';
$handler = 'https://tk.tandem-consult.ru/applications/itr_ai_bot/index.php';
$data = [
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => "CRM_DYNAMIC_1186_DETAIL_TAB",
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => "LEFT_MENU",
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => "TASK_VIEW_TAB",
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => "CRM_DYNAMIC_1046_DETAIL_TAB",
                        "HANDLER"   => $handler,
                        "TITLE"     => $title,
                ]
        ],
        [
                'method' => 'placement.bind',
                'params' => [
                        "PLACEMENT" => "CRM_COMPANY_DETAIL_TAB",
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