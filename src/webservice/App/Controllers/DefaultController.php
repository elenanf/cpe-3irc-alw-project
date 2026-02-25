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

}
