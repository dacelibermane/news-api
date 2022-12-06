<?php declare(strict_types=1);

session_start();

require_once "../vendor/autoload.php";

use App\Controllers\ArticleController;
use App\Controllers\RegisterController;
use App\Controllers\LoginController;
use App\Controllers\UserAccountController;
use App\Controllers\LogoutController;
use App\Redirect;
use App\Template;
use App\ViewVariables\AuthViewVariables;
use App\ViewVariables\ErrorsViewVariables;
use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


(Dotenv::createImmutable('../'))->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', [ArticleController::class, 'index']);
    $route->addRoute('GET', '/register', [RegisterController::class, 'showForm']);
    $route->addRoute('POST', '/register', [RegisterController::class, 'register']);
    $route->addRoute('GET', '/login', [LoginController::class, 'showForm']);
    $route->addRoute('POST', '/login', [LoginController::class, 'login']);
    $route->addRoute('GET', '/account', [UserAccountController::class, 'showAccount']);
    $route->addRoute('POST', '/account', [UserAccountController::class, 'changeEmail']);
    $route->addRoute('POST', '/account', [UserAccountController::class, 'changePassword']);
    $route->addRoute('GET', '/logout', [LogoutController::class, 'logout']);
});

$loader = new FilesystemLoader('../views');
$twig = new Environment($loader);

$authVariables = [
    AuthViewVariables::class,
    ErrorsViewVariables::class
];

foreach ($authVariables as $variable) {
    $variable = new $variable;
    $twig->addGlobal($variable->getName(), $variable->getValue());
}

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$controller, $method] = $handler;
        $response = (new $controller)->{$method}();

        if ($response instanceof Template) {
            echo $twig->render($response->getPath(), $response->getParameters());
            unset($_SESSION['errors']);
        }

        if ($response instanceof Redirect) {
            header('Location: ' . $response->getUrl());
        }
        break;
}