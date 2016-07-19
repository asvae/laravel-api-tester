<?php

namespace Asvae\ApiTester;

use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Illuminate\Routing\Router;

/**
 * Class DefaultRouteRepository
 *
 * @package \Asvae\ApiTester
 */
class DefaultRouteRepository implements RouteRepositoryInterface
{

    /**
     * @type \Asvae\ApiTester\RouteCollection
     */
    protected $routes;

    public function __construct(Router $router, RouteCollection $collection)
    {
        $this->routes = $collection;

        foreach ($router->getRoutes() as $route) {
            /** @var \Illuminate\Routing\Route $route */
            $this->routes->push([
                'name'   => $route->getName(),
                'method' => $route->getMethods()[0],
                'path'   => $route->getPath(),
                'action' => $route->getActionName(),
            ]);
        }
    }

    /**
     * @param array $match
     * @param array $except
     *
     * @return \Asvae\ApiTester\RouteCollection
     */
    public function get($match = [], $except = [])
    {
        return $this->routes->filterMatch($match)->filterExcept($except)->values();
    }
}
