<?php
use Bitrix\Main\Routing\RoutingConfigurator;
use Faleev\Tender\Controller\AjaxController;
use Faleev\Tender\Controller\AppController;

return function (RoutingConfigurator $routes) {
    $routes->prefix('api/faleev.tender')->group(function (RoutingConfigurator $routes) {
        // Основная синхронизация
        $routes->any('sync/', [AjaxController::class, 'sync']);
        
        // Сервисные методы
        $routes->get('test/', [AppController::class, 'test']);
        $routes->get('subscribe/', [AppController::class, 'subscribe']);
    });
};
