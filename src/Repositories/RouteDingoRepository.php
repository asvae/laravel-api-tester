<?php

namespace Asvae\ApiTester\Repositories;

use Asvae\ApiTester\Collections\RouteCollection;
use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Dingo\Api\Routing\Router;
use ReflectionClass;

/**
 * Class RouteDingoRepository
 *
 * @package \Asvae\ApiTester\Repositories
 */
class RouteDingoRepository implements RouteRepositoryInterface
{
    /**
     * @type \Asvae\ApiTester\Collections\RouteCollection
     */
    protected $routes;

    public function __construct(RouteCollection $collection, Router $router)
    {
        $this->routes = $collection;

        foreach ($router->getAdapterRoutes() as $versionName => $versionGroup) {
            foreach ($versionGroup as $route) {

                /** @var \Illuminate\Routing\Route $route */
                $this->routes->push([
                    'router'  => 'Dingo',
                    'version' => $versionName,
                    'wheres'  => $this->extractWheres($route),
                    'domain'  => $route->domain(),
                    'name'    => $route->getName(),
                    'methods' => $route->getMethods(),
                    'path'    => $route->getPath(),
                    'action'  => $route->getAction(),
                ]);
            }
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

    protected function extractWheres($route)
    {
        $reflection = new ReflectionClass($route);
        $prop = $reflection->getProperty('wheres');
        $prop->setAccessible(true);

        $wheres = $prop->getValue($route);

        // Хак, что бы в json всегда был объект
        if (empty($wheres)) {
            return (object) [];
        }

        return $wheres;
    }
}
