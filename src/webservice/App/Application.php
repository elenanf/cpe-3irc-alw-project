<?php

namespace App;

use App\Controllers\DefaultController;
use App\Controllers\PlayerSaveController;
use CPE\Framework\AbstractApplication;
use CPE\Framework\Router;

class Application extends AbstractApplication
{
    public function run()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        session_start();

        // map all routes to corresponding controllers/actions
        $this->router = new Router($this);
        $this->router->mapDefault(DefaultController::class, 'error404');

        $this->router->map('GET', '/api/players/{string:name}/inventory', PlayerSaveController::class, 'getPlayersInventory');
        $this->router->map('GET', '/api/players/{string:name}/buildings', PlayerSaveController::class, 'getPlayersBuildings');


        $route = $this->router->findRoute();
        $controller = $this->router->getController($route->controller);
        $controller->execute($route->action, $route->foundParams);
    }
}
