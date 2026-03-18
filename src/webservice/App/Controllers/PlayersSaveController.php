<?php

namespace App\Controllers;

use App\Repositories\GameConfigRepository;
use App\Repositories\SaveRepository;
use App\Repositories\UserRepository;
use CPE\Framework\AbstractApplication;
use CPE\Framework\AbstractController;

class PlayersSaveController extends AbstractController
{
    protected $saveRepository;
    protected $userRepository;
    protected $gameConfig;

    public function __construct(AbstractApplication $app)
    {
        $this->saveRepository = new SaveRepository("Data/Saves/", "Data/Config/save_initial.json");
        $this->userRepository = new UserRepository("Data/users.json");
        $this->gameConfig = new GameConfigRepository("Data/Config/game_config.json");
        parent::__construct($app);
    }

    public function getPlayersInventory()
    {
        $user = $this->userRepository->get($this->parameters["name"]);

        if (!$user) {
            http_response_code(404);
            exit();
        }

        $response = $this->saveRepository->getInventory($user->login);
        echo json_encode($response);
    }

    public function getPlayersBuildings()
    {
        $user = $this->userRepository->get($this->parameters["name"]);

        if (!$user) {
            http_response_code(404);
            exit();
        }

        $response = $this->saveRepository->getBuildings($user->login);
        echo json_encode($response);
    }

    public function getProduct(): void
    {
        $user = $this->userRepository->get($this->parameters["name"]);
        $product = $this->parameters["product"];

        if (!$user || !$product) {
            http_response_code(404);
            exit();
        }

        $response = $this->saveRepository->getProduct($user->login, $product);
        echo json_encode($response);
    }

    public function setProduct(): void
    {
        $user = $this->userRepository->get($this->parameters["name"]);
        $product = $this->parameters["product"];
        $value = $this->parameters["value"];

        if (!$user || !$product || !$value) {
            http_response_code(404);
            exit();
        }

        $this->saveRepository->setProduct($user->login, $product, $value);
    }

    public function getBuildingLevel(): void
    {
        $user = $this->userRepository->get($this->parameters["name"]);
        $building = $this->parameters["building"];

        if (!$user || !$building) {
            http_response_code(404);
            exit();
        }

        $response = $this->saveRepository->getLevel($user->login, $building);
        echo json_encode($response);
    }

    public function setBuildingLevel(): void
    {
        $user = $this->userRepository->get($this->parameters["name"]);
        $building = $this->parameters["building"];
        $value = $this->parameters["value"];

        if (!$user || !$building || !$value) {
            http_response_code(404);
            exit();
        }

        $this->saveRepository->setLevel($user->login, $building, $value);
    }

    public function harvest(): void
    {
        $user = $this->userRepository->get($this->parameters["name"]);
        $buildingName = $this->parameters["buildingName"];

        if (!$user || !$buildingName) {
            http_response_code(404);
            exit();
        }

        $save = $this->saveRepository->load($user->login);
        $inventory = $save->inventory;

        $building = $this->gameConfig->getBuilding($buildingName);
        $production = $building->production;

        $productionAmount = $save->buildings->{$buildingName}->level;
        $inventory->$production += $productionAmount;

        $save->inventory = $inventory;
        $this->saveRepository->save($user->login, $save);

        header('Content-Type: application/json');
        echo json_encode($save);
    }

    public function upgrade(): void
    {
        $user = $this->userRepository->get($this->parameters["name"]);
        $buildingName = $this->parameters["buildingName"];

        if (!$user || !$buildingName) {
            http_response_code(404);
            exit();
        }

        $save = $this->saveRepository->load($user->login);
        $inventory = $save->inventory;
        $building = $this->gameConfig->getBuilding($buildingName);

        $costResource = $building->cost;
        $updatingCost = $this->gameConfig->getUpgradeCost($buildingName, ($save->buildings->{$buildingName}->level + 1));

        if(($inventory->{$costResource} - $updatingCost) > 0) {
            $inventory->{$costResource} -= $updatingCost;
        } else {
            echo json_encode("Inventaire insuffisant");
            exit();
        }

        $save->buildings->{$buildingName}->level += 1;

        $save->inventory = $inventory;
        $this->saveRepository->save($user->login, $save);

        header('Content-Type: application/json');
        echo json_encode($save);
    }
}
