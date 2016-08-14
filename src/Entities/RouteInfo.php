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

    /**
     * @var \Illuminate\Routing\Route
     */
    private $route;

    public function __construct(\Illuminate\Routing\Route $route, $options = [])
    {
        $this->route = $route;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'router' => 'Laravel',
            'name' => $this->route->getName(),
            'methods' => $this->route->getMethods(),
            'domain' => $this->route->domain(),
            'path' => $this->route->getPath(),
            'action' => $this->route->getAction(),
            'annotation' => $this->extractAnnotation(),
            'formRequest' => $this->extractFormRequest(),
            'wheres' => $this->extractWheres(),
        ];
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
        foreach ($this->getActionReflection()->getParameters() as $parameter){
            $type = $parameter->getType();
            if($type && is_subclass_of($type->__toString(), FormRequest::class) ){
                $formRequest = app()->build($type->__toString());
                $rules = (new \ReflectionClass($formRequest))->getMethod('rules')->invoke($formRequest);

                return [
                    'class' => $type->__toString(),
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

        if(is_callable($uses)){
            return $this->actionReflection = new \ReflectionFunction($uses);
        }
    }
}
