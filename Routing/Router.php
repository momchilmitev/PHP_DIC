<?php

namespace Routing;

class Router implements RouterInterface
{

    private array $routes = [];

    public function __construct()
    {
        $this->routes['GET'] = [];
        $this->routes['POST'] = [];
    }


    public function registerRout(string $route, string $requestMethod, callable $handler): RouterInterface
    {
        $this->routes[$requestMethod][$route] = $handler;

        return $this;
    }

    public function invoke(string $uri, string $requestMethod): bool
    {
        foreach ($this->routes[$requestMethod] as $route => $handler) {
            $res = preg_match_all('/^' . $route . '$/', $uri, $matches);

            if (!$res) {
                continue;
            }

            $handler($matches);
            return true;
        }

        return false;
    }
}