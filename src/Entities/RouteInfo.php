<?php

namespace Asvae\ApiTester\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use JsonSerializable;
use ReflectionException;

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
     * @var array
     */
    protected $errors = [];

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
            'errors' => $this->errors,
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

    /**
     * @return string
     */
    protected function extractAnnotation()
    {
        $reflection = $this->getActionReflection();

        if (!is_null($reflection)) {
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
            $class = $parameter->getClass();

            // Если аргумент нетипизирован, значит он уже не будет затянут через DI,
            // И дальнейший обход не имеет смысла, так как все последующие аргументы
            // тоже не будут затянуты через DI, не зависимо от того типизированы они или нет.
            if (is_null($class)) {
                break;
            }

            $class = $class->__toString();

            // Если это форм-реквест.
            if (is_subclass_of($class, FormRequest::class)) {

                // Для вызова нестатического метода на объекте, нам необходим инстанс объекта.
                // Мы используем build вместо make, чтобы избежать автоматического запуска валидации.
                $formRequest = app()->build($class);

                // Здесь используется метод call, чтобы разрешить зависимости.
                $rules = app()->call([$formRequest, 'rules']);

                return [
                    'class' => $class,
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
     * @return \ReflectionFunctionAbstract|null
     */
    protected function getActionReflection()
    {
        if ($this->actionReflection) {
            return $this->actionReflection;
        }

        $uses = $this->route->getAction()['uses'];

        // Если это строка и она содержит @, значит мы имем дело с методом контроллера.
        if (is_string($uses) && str_contains($uses, '@')) {
            list($controller, $action) = explode('@', $uses);

            // Если нет контроллера.
            if (!class_exists($controller)) {
                $this->setError('uses', 'controller does not exists');
                return null;
            }

            // Если нет метода в контроллере.
            if (!method_exists($controller, $action)) {
                $this->setError('uses', 'controller@method does not exists');
                return null;
            }

            return $this->actionReflection = new \ReflectionMethod($controller, $action);
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

    private function setError($type, $text, $params = [])
    {
        $this->errors[$type] = trans($text, $params);
    }
}
