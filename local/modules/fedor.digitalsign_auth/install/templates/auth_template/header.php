<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Loader;
use Fedor\DigitalsignAuth\Helpers\AuthorizeCheck;

global $APPLICATION, $USER;

//подключение необходимых модулей и файлов
Loader::includeModule('fedor.digitalsign_auth'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php $APPLICATION->ShowTitle()?></title>

    <?php
    $APPLICATION->ShowHead();
    CJSCore::Init(array("fx"));

    // Для подключения css
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/assets/css/bootstrap.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/assets/css/notyf.min.css");
    //Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/assets/css/auth.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/assets/css/custom.css");

    // Для подключения скриптов
    //Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/assets/js/setFormatBurgerMenu.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/assets/js/notyf.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/assets/js/cadesplugin_api.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/assets/js/cades_function.js");
    ?>
</head>
<body class="d-flex flex-column">
<main id="main" class="flex-shrink-0 mb-auto">
    <div class="container-fluid">
        <?php
        $auth = new AuthorizeCheck();
        $auth->AuthForm('');
        ?>
