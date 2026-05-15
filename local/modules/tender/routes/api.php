<?php
use Bitrix\Main\Routing\RoutingConfigurator;
use Tender\Controller\AjaxController;
use Tender\Controller\AppController;

return function (RoutingConfigurator $routes) {
    $routes->prefix('api/tender')->group(function (RoutingConfigurator $routes) {
        // Основная синхронизация
        $routes->any('sync/', [AjaxController::class, 'sync']);
        
        // Сервисные методы
        $routes->get('test/', [AppController::class, 'test']);
        $routes->get('subscribe/', [AppController::class, 'subscribe']);
    });
};
