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
        $this->twig->render('404.html.twig');
    }

    public function login()
    {
        $repo = new UserRepository("Data/users.json");

        $error = $_SESSION["error"] ?? null;
        unset($_SESSION["error"]);

        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            if ($_POST['username'] != "" || $_POST['password'] != "") {
                try {
                    $user = $repo->get($_POST["username"]);
                    if ($user != null && password_verify($_POST["password"], $user->password_hash)) {
                        $_SESSION["user"]["login"] = $user->login;
                        header('Location: /dashboard', true, 302);
                    } else {
                        $_SESSION["error"] = "Le login ou le mot de passe est incorrect";
                        header('Location: /login', true, 302);
                    }
                } catch (Exception $e) {
                    $_SESSION["error"] = $e->getMessage();
                    header('Location: /login', true, 302);
                    exit();
                }
            } else {
                $_SESSION["error"] = "Veuillez saisir nom d'utilisateur ET mot de passe";
                header('Location: /login', true, 302);
                exit();
            }

        }

        $session = false;
        $username = null;
        if (!empty($_SESSION["user"])) {
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
        $username = NULL;
        if (empty($_SESSION["user"])) {
            http_response_code(401);
            $session = false;
        } else {
            $username = $_SESSION["user"]["login"];
        }

        echo $this->twig->render('dashboard.html.twig', [
            'session' => $session,
            'buildings' => (array)$gameConfigRepository->getBuildings(),
            'products' => (array)$gameConfigRepository->getProducts(),
            'username' => $username
        ]);
    }

    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit();
    }
}
