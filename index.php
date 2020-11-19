<?php

use Routing\Router;

spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$router = new Router();

require_once './routes.php';

$self = $_SERVER['PHP_SELF'];
$junk = str_replace('index.php', '', $self);

$uri = str_replace($junk, '', $_SERVER['REQUEST_URI']);
$uriInfo = explode('/', $uri);

$controllerName = array_shift($uriInfo);

$methodName = array_shift($uriInfo);

$app = new \Core\Application($controllerName,$methodName,$uriInfo,$router,$uri, $_SERVER);
$app->start();
