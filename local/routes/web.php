<?php
define('NOT_CHECK_PERMISSIONS', true);
define('NO_KEEP_STATISTIC', true);
define("NEED_AUTH", false);

use Bitrix\Main\Routing\RoutingConfigurator;
use Faleev\Tender\Controller\AjaxController;
use Faleev\Tender\Controller\AppController;
use Faleev\Tender\Controller\AuthController;

return function (RoutingConfigurator $routes) {
    // Тут могут быть ваши старые роуты...
    // Добавляем наш блок API

    $routes->prefix('api/tender')->group(function (RoutingConfigurator $routes) {
		/** todo:: образец для использования */
	    $routes->get('new/{id}', [AuthController::class, 'getTenderById'])
		    ->name('getTenderById');
        $routes->any('sync/', [AjaxController::class, 'sync']);
        $routes->get('test/', [AppController::class, 'test']);
        $routes->get('subscribe/', [AppController::class, 'subscribe']);
    });
};
