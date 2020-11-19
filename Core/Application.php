<?php

namespace Core;

use Routing\RouterInterface;

class Application
{
  private $controllerName;

  private $actionName;

  private array $params = [];

  private RouterInterface $router;

  private $uri;

  private $serverInfo;

  public function __construct($controllerName, $actionName, array $params, RouterInterface $router, $uri, $serverInfo)
  {
    $this->controllerName = $controllerName;
    $this->actionName = $actionName;
    $this->params = $params;
    $this->router = $router;
    $this->uri = $uri;
    $this->serverInfo = $serverInfo;
  }

  public function start()
  {
      $fullControllerName = 'Controllers\\' . ucfirst($this->controllerName) . 'Controller';

      if (!class_exists($fullControllerName) || !method_exists($fullControllerName, $this->actionName)) {
          if (!$this->router->invoke($this->uri, $this->serverInfo['REQUEST_METHOD'])) {
              http_response_code(404);
              echo "<h1> 404 Not Found </h1>";
          }
          exit;
      }

      $controllerInstance = new $fullControllerName();

      call_user_func_array([$controllerInstance, $this->actionName], $this->params);
  }
}