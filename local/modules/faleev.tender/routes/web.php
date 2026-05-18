<?php
use Bitrix\Main\Routing\RoutingConfigurator;
use Faleev\Tender\Controller\AjaxController;
use Faleev\Tender\Controller\AppController;

return function (RoutingConfigurator $routes) {
    // Тут могут быть ваши старые роуты...

    // Добавляем наш блок API
    $routes->prefix('api/faleev.tender')->group(function (RoutingConfigurator $routes) {
        $routes->any('sync/', [AjaxController::class, 'sync']);
        $routes->get('test/', [AppController::class, 'test']);
        $routes->get('subscribe/', [AppController::class, 'subscribe']);
    });
};
