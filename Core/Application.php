<?php

namespace Core;

use Routing\RouterInterface;

class Application
{
  private MvcContextInterface $mvcContext;

  private RouterInterface $router;

  private string $uri;

  private array $serverInfo;

  public function __construct(MvcContextInterface $mvcContext, RouterInterface $router, $uri, $serverInfo)
  {
    $this->mvcContext = $mvcContext;
    $this->router = $router;
    $this->uri = $uri;
    $this->serverInfo = $serverInfo;
  }

  public function start()
  {
      $fullControllerName = 'Controllers\\' . ucfirst($this->mvcContext->getControllerName()) . 'Controller';

      if (!class_exists($fullControllerName) || !method_exists($fullControllerName, $this->mvcContext->getActionName())) {
          if (!$this->router->invoke($this->uri, $this->serverInfo['REQUEST_METHOD'])) {
              http_response_code(404);
              echo "<h1> 404 Not Found </h1>";
          }
          exit;
      }

      $controllerInstance = new $fullControllerName();

      call_user_func_array([$controllerInstance, $this->mvcContext->getActionName()], $this->mvcContext->getParams());
  }
}