<?php

namespace App\Controllers;

use App\Repositories\SaveRepository;
use App\Repositories\UserRepository;
use CPE\Framework\AbstractController;

class PlayerSaveController extends AbstractController
{
    public function getPlayersInventory()
    {
        $userRepository = new UserRepository("Data/users.json");
        $user = $userRepository->get($this->parameters["name"]);

        if (!$user) {
            http_response_code(404);
            exit();
        }

        $saveRepository = new SaveRepository("Data/Saves/", "Data/Config/save_initial.json");

        $response = $saveRepository->getInventory($user->login);
        echo json_encode($response);
    }

    public function getPlayersBuildings()
    {
        $userRepository = new UserRepository("Data/users.json");
        $user = $userRepository->get($this->parameters["name"]);

        if (!$user) {
            http_response_code(404);
            exit();
        }

        $saveRepository = new SaveRepository("Data/Saves/", "Data/Config/save_initial.json");

        $response = $saveRepository->getBuildings($user->login);
        echo json_encode($response);
    }

}
