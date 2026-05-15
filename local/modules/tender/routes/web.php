<?php
use Bitrix\Main\Routing\RoutingConfigurator;
use Tender\Controller\AjaxController;
use Tender\Controller\AppController;

return function (RoutingConfigurator $routes) {
    // Тут могут быть ваши старые роуты...

    // Добавляем наш блок API
    $routes->prefix('api/tender')->group(function (RoutingConfigurator $routes) {
        $routes->any('sync/', [AjaxController::class, 'sync']);
        $routes->get('test/', [AppController::class, 'test']);
        $routes->get('subscribe/', [AppController::class, 'subscribe']);
    });
};
