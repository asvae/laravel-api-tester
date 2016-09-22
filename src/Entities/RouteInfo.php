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
     * @var \ReflectionClass|null
     */
    protected $routeReflection = null;

    /**
     * @var \ReflectionFunctionAbstract|null
     */
    protected $actionReflection = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var bool
     */
    protected $addMeta;

    /**
     * @var \Illuminate\Routing\Route
     */
    private $route;

    public function __construct($route, $options = [])
    {
        $this->route = $route;
        $this->options = $options;
        $this->addMeta = config('api-tester.route_meta');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge([
            'name'    => $this->route->getName(),
            'methods' => $this->route->getMethods(),
            'domain'  => $this->route->domain(),
            'path'    => $this->preparePath(),
            'action'  => $this->route->getAction(),
            'wheres'  => $this->extractWheres(),
            'errors'  => $this->errors,
        ], $this->getMeta(), $this->options);
    }

    protected function extractWheres()
    {
        $prop = $this->getRouteReflection()->getProperty('wheres');
        $prop->setAccessible(true);

        $wheres = $prop->getValue($this->route);

        // Хак, чтобы в json всегда был объект
        if (empty($wheres)) {
            return (object) [];
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

    /**
     * @return string
     */
    protected function extractDocBlocks()
    {
        $reflection = $this->getActionReflection();

        if (! is_null($reflection)) {
            return $reflection->getDocComment();
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

            // If first argument is untyped Laravel DI won't check any other
            // arguments. So we will take a break as well.
            try {
                $class = $parameter->getClass();
            } catch (\ReflectionException $e) {
                break;
            }

            if (is_null($class)) {
                break;
            }

            // We don't care if argument is anything but FormRequest
            if (! is_subclass_of($class->name, FormRequest::class)) {
                continue;
            }

            // To trigger non static method on object we have to instantiate it.
            // We will `build` instead of `make` so the validation won't be triggered.
            $formRequest = app()->build($class->name);

            // Next we use `call` to resolve dependencies.
            $rules = app()->call([$formRequest, 'rules']);

            return [
                'class' => $class->name,
                'rules' => $rules,
            ];
        }

        return null;
    }

    /**
     * @return \ReflectionClass
     */
    protected function getRouteReflection()
    {
        if ($this->routeReflection) {
            return $this->routeReflection;
        }

        return $this->routeReflection = new \ReflectionClass($this->route);
    }

    /**
     * @return \ReflectionFunctionAbstract|null
     */
    protected function getActionReflection()
    {
        if ($this->actionReflection) {
            return $this->actionReflection;
        }

        $uses = $this->route->getAction()['uses'];

        // `uses` is string and contains '@'
        // means we're looking at controller@method
        if (is_string($uses) && str_contains($uses, '@')) {
            list($controller, $action) = explode('@', $uses);

            // Controller is missing.
            if (! class_exists($controller)) {
                $this->setError('uses', 'controller does not exists');

                return null;
            }

            // Action is missing.
            if (! method_exists($controller, $action)) {
                $this->setError('uses', 'controller@method does not exists');

                return null;
            }

            return $this->actionReflection = new \ReflectionMethod($controller,
                $action);
        }

        if (is_callable($uses)) {
            return $this->actionReflection = new \ReflectionFunction($uses);
        }

        $this->setError('uses', 'route uses is not valid');

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

    protected function setError($type, $text, $params = [])
    {
        $this->errors[$type] = trans($text, $params);
    }

    /**
     * @return array
     */
    protected function getMeta()
    {
        if ($this->addMeta) {
            return [
                'annotation'  => $this->extractDocBlocks(),
                'formRequest' => $this->extractFormRequest(),
                'errors'      => $this->errors,
            ];
        }

        return [];
    }
}
