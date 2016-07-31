<?php

namespace Asvae\ApiTester\Repositories;

use Asvae\ApiTester\Collections\RouteCollection;
use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Illuminate\Routing\Router;

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
            /** @var \Illuminate\Routing\Route $route */
            $this->routes->push([
                'router'  => 'Laravel',
                'name'    => $route->getName(),
                'methods' => $route->getMethods(),
                'domain'  => $route->domain(),
                'path'    => $route->getPath(),
                'action'  => $route->getAction(),
            ]);
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
