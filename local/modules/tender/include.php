<?php
use Bitrix\Main\Loader;
use Tender\Handlers\LogHandler;

Loader::registerAutoLoadClasses(
	'tender',
	[
		LogHandler::class =>
			'lib/Handlers/LogHandler.php',
	]
);

//AddEventHandler(
//	'crm',
//	'onCrmDynamicItemAdd',
//	['\\Tender\\Handlers\\LogHandler', 'newHandler']
//);

