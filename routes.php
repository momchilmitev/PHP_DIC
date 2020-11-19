<?php

/**
 * @var \Routing\Router $router
 */

$router->registerRout(
    'profile\/(.*?)\/edit',
    'GET',
    function($matches) {
        (new \Controllers\UsersController())->editProfile($matches[1][0]);
    }
);

$router->registerRout(
    'profile\/(.*?)\/edit',
    'POST',
    function($matches) {
        (new \Controllers\UsersController())->editProfileProcess($matches[1][0]);
    }
);