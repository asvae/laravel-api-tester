<?php

namespace Asvae\ApiTester\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Http\FormRequest;
use JsonSerializable;

/**
 * Frontend ready route
 */
class RouteInfo implements Arrayable, JsonSerializable
{
    /**
     * @var \ReflectionFunctionAbstract
     */
    protected $routeReflection;


    /**
     * @var
     */
    protected $actionReflection;
    protected $options;

    /**
     * @var \Illuminate\Routing\Route
     */
    private $route;

    public function __construct(\Illuminate\Routing\Route $route, $options = [])
    {
        $this->route = $route;
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge([
            'name' => $this->route->getName(),
            'methods' => $this->route->getMethods(),
            'domain' => $this->route->domain(),
            'path' => $this->preparePath(),
            'action' => $this->route->getAction(),
            'annotation' => $this->extractAnnotation(),
            'formRequest' => $this->extractFormRequest(),
            'wheres' => $this->extractWheres(),
        ], $this->options);
    }

    protected function extractWheres()
    {
        $prop = $this->getRouteReflection()->getProperty('wheres');
        $prop->setAccessible(true);

        $wheres = $prop->getValue($this->route);

        // Хак, чтобы в json всегда был объект
        if (empty($wheres)) {
            return (object)[];
        }

        return $wheres;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        return $this->toArray();
    }

    protected function extractAnnotation()
    {
        $uses = $this->route->getAction()['uses'];
        if (is_string($uses)) {
            list($controller, $action) = explode('@', $uses);
            return (new \ReflectionClass($controller))->getMethod($action)->getDocComment();
        }

        return '';
    }

    protected function extractFormRequest()
    {
        $reflection = $this->getActionReflection();

        if (is_null($reflection)) {
            return null;
        }

        foreach ($reflection->getParameters() as $parameter) {
            $class = $parameter->getClass();
            if ($class && is_subclass_of($class->__toString(), FormRequest::class)) {
                $formRequest = app()->build($class->__toString());
                $rules = (new \ReflectionClass($formRequest))->getMethod('rules')->invoke($formRequest);

                return [
                    'class' => $class->__toString(),
                    'rules' => $rules,
                ];
            }
        }

        return null;
    }

    protected function getRouteReflection()
    {
        if ($this->routeReflection) {
            return $this->routeReflection;
        }


        return $this->routeReflection = new \ReflectionClass($this->route);
    }

    /**
     * @return \ReflectionFunctionAbstract
     */
    protected function getActionReflection()
    {
        if ($this->actionReflection) {
            return $this->actionReflection;
        }

        $uses = $this->route->getAction()['uses'];
        if (is_string($uses)) {
            list($controller, $action) = explode('@', $uses);
            return $this->actionReflection = new \ReflectionMethod($controller, $action);
        }

        if (is_callable($uses)) {
            return $this->actionReflection = new \ReflectionFunction($uses);
        }

        return null;
    }

    protected function preparePath()
    {
        $path = $this->route->getPath();
        if ($path === '/') {
            return $path;
        }

        return trim($path, '/');
    }
}
