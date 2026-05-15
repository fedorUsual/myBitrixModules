<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

return [
	'js' => 'dist/app.bundle.js',
	// 'css' => 'dist/app.bundle.css', // Раскомментируйте, если есть стили
	'rel' => [
		'main.core',
		'ui.react-base', // Это даст вам React и ReactDOM из ядра Битрикса
	],
	'skip_core' => false,
];
