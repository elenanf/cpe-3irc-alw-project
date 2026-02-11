<?php

namespace App\Controllers;

use App\Repositories\GameConfigRepository;
use App\Repositories\UserRepository;
use CPE\Framework\AbstractApplication;
use CPE\Framework\AbstractController;
use Exception;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class DefaultController extends AbstractController
{
    protected $twig;
    public function __construct(AbstractApplication $app)
    {
        parent::__construct($app);
        $this->twig = new Environment(new FilesystemLoader('App/Templates'), [
            'cache' => false,
            'debug' => true,
        ]);
        $this->twig->addExtension(new DebugExtension());
    }

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

        $session = false;
        if (!empty($_SESSION)) {
            $session = true;
            $username = $_SESSION["user"]["login"];
        }


        echo $this->twig->render('login.html.twig', [
            'session' => $session,
            'error' => $error,
            'username' => $username
        ]);
    }

    public function dashboard()
    {
        $gameConfigRepository = new GameConfigRepository("Data/Config/game_config.json");

        $session = true;
        if (empty($_SESSION)) {
            http_response_code(401);
            $session = false;
        }

        echo $this->twig->render('dashboard.html.twig', [
            'session' => $session,
            'buildings' => (array)$gameConfigRepository->getBuildings(),
            'products' => (array)$gameConfigRepository->getProducts()]);
    }
}
