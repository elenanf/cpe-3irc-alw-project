<?php

namespace App;

use App\Controllers\DefaultController;
use App\Controllers\PlayersSaveController;
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

        $this->router->map('GET', '/api/players/{string:name}/inventory', PlayersSaveController::class, 'getPlayersInventory');
        $this->router->map('GET', '/api/players/{string:name}/buildings', PlayersSaveController::class, 'getPlayersBuildings');
        $this->router->map('GET', '/api/players/{string:name}/inventory/{string:product}', PlayersSaveController::class, 'getProduct');

        $route = $this->router->findRoute();
        $controller = $this->router->getController($route->controller);
        $controller->execute($route->action, $route->foundParams);
    }
}
