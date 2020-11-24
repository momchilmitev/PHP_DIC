<?php

namespace Core;

use Routing\RouterInterface;

class Application
{
  private MvcContextInterface $mvcContext;

  private RouterInterface $router;

  private string $uri;

  private array $serverInfo;

  private array $dependencies = [];

  private array $resolvedDependencies = [];

  public function __construct(MvcContextInterface $mvcContext, RouterInterface $router, $uri, $serverInfo)
  {
    $this->mvcContext = $mvcContext;
    $this->router = $router;
    $this->uri = $uri;
    $this->serverInfo = $serverInfo;
    $this->dependencies[MvcContextInterface::class] = get_class($mvcContext);
    $this->resolvedDependencies[get_class($mvcContext)] = $mvcContext;
  }

  public function registerDependency(string $abstraction, string $implementation)
  {
      $this->dependencies[$abstraction] = $implementation;
  }

  public function resolve($className)
  {
      if (array_key_exists($className, $this->resolvedDependencies)) {
          return $this->resolvedDependencies[$className];
      }

      $classInfo = new \ReflectionClass($className);
      $constructor = $classInfo->getConstructor();

      if ($constructor === null) {
          $obj = new $className;
          $this->resolvedDependencies[$className] = $obj;

          return $obj;
      }

      $params = $constructor->getParameters();

      $resolvedParams = [];
      for ($i = 0; $i < count($params); $i++) {
          $param = $params[$i];
          $dependencyInterface = $param->getClass();
          $dependencyClass = $this->dependencies[$dependencyInterface->getName()];
          $dependencyInstance= $this->resolve($dependencyClass);
          $resolvedParams[] = $dependencyInstance;
      }

      $obj = $classInfo->newInstanceArgs($resolvedParams);

      $this->resolvedDependencies[$className] = $obj;

      return $obj;

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

      $controllerInstance = $this->resolve($fullControllerName);

      $getParams = $this->mvcContext->getParams();
      $paramCount = count($getParams);
      $methodParams = array_merge([], $getParams);

      $methodInfo = new \ReflectionMethod($controllerInstance, $this->mvcContext->getActionName());
      $paramsInfo = $methodInfo->getParameters();

      for ($i = $paramCount; $i < count($paramsInfo); $i++) {
          $param = $paramsInfo[$i];
          $paramInterface = $param->getClass();
          $paramInterfaceName = $paramInterface->getName();
          $paramClassName = $this->dependencies[$paramInterfaceName];
          $paramInstance = $this->resolve($paramClassName);
          $methodParams[] = $paramInstance;
      }

      call_user_func_array([$controllerInstance, $this->mvcContext->getActionName()], $methodParams);

  }
}