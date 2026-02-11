<?php

namespace App\Controllers;

use App\Repositories\GameConfigRepository;
use App\Repositories\UserRepository;
use CPE\Framework\AbstractController;
use Exception;

class DefaultController extends AbstractController
{
    public function index()
    {
        $data = "Bonjour le monde !";
        $this->app->view()->setParam('pageTitle', $data);
        $this->app->view()->render('homepage.tpl.php');
    }

    public function test()
    {
        echo '<p>Cette page a reçu un paramètre nommé "nombre" et valant "' . $this->parameters['nombre'] . '"</p>
              <p>Contenu complet de <code>$this->parameters</code>:</p>
              <pre>';
        print_r($this->parameters);
    }

    public function error404()
    {
        http_response_code(404);
        $this->app->view()->render('404.tpl.php');
    }

    public function login()
    {
        $error = null;

        $repo = new UserRepository("Data/users.json");

        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            if ($_POST['username'] != "" || $_POST['password'] != "") {
                try {
                    $user = $repo->get($_POST["username"]);
                    if ($user != null && password_verify($_POST["password"], $user->password_hash)) {
                        $_SESSION["user"]["login"] = $user->login;
                    } else {
                        $error = "Le login ou le mot de passe est incorrect";
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            } else {
                $error = "Veuillez saisir le nom d'utilisateur et le password";
            }


        }
        $this->app->view()->setParam('error', $error);
        $this->app->view()->render('login.tpl.php');
    }

    public function dashboard()
    {
        $gameConfigRepository = new GameConfigRepository("Data/Config/game_config.json");

        if (empty($_SESSION)) {
            http_response_code(401);
        }

        $this->app->view()->setParam('gameConfigRepository', $gameConfigRepository);
        $this->app->view()->render('dashboard.tpl.php');

    }
}
