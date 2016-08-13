<?php

namespace Asvae\ApiTester\Entities;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Frontend ready route
 */
class RouteInfo implements Arrayable, JsonSerializable
{
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
            'router'  => 'Laravel',
            'wheres'  => $this->extractWheres(),
            'name'    => $this->route->getName(),
            'methods' => $this->route->getMethods(),
            'domain'  => $this->route->domain(),
            'path'    => $this->route->getPath(),
            'action'  => $this->route->getAction(),
        ];
    }

    protected function extractWheres()
    {
        $reflection = new \ReflectionClass($this->route);
        $prop = $reflection->getProperty('wheres');
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
}
