<?php

namespace Asvae\ApiTester\Repositories;

use Asvae\ApiTester\Collections\RouteCollection;
use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Asvae\ApiTester\Entities\RouteInfo;
use Illuminate\Routing\Router;
use ReflectionClass;

class RouteLaravelRepository implements RouteRepositoryInterface
{
    /**
     * @type \Asvae\ApiTester\Collections\RouteCollection
     */
    protected $routes;

    public function __construct(RouteCollection $collection, Router $router)
    {
        $this->routes = $collection;

        foreach ($router->getRoutes() as $route) {

            $routeInfo = (new RouteInfo($route))->toArray();

            /** @var \Illuminate\Routing\Route $route */
            $this->routes->push($routeInfo);
        }
    }

    /**
     * @param array $match
     * @param array $except
     *
     * @return \Asvae\ApiTester\Collections\RouteCollection
     */
    public function get($match = [], $except = [])
    {
        return $this->routes->filterMatch($match)
                            ->filterExcept($except)
                            ->values();
    }
}
