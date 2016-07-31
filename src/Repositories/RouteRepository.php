<?php

namespace Asvae\ApiTester\Repositories;

use Asvae\ApiTester\Collections\RouteCollection;
use Asvae\ApiTester\Contracts\RouteRepositoryInterface;

/**
 * Class RouteRepository
 *
 * @package \Asvae\ApiTester\Repositories
 */
class RouteRepository implements RouteRepositoryInterface
{
    /**
     * @type \Asvae\ApiTester\Contracts\RouteRepositoryInterface[]
     */
    protected $repositories;

    /**
     * @type \Asvae\ApiTester\Collections\RouteCollection
     */
    protected $routes;

    public function __construct(RouteCollection $routes, $repositories)
    {
        $this->routes = $routes;
        $this->repositories = $repositories;
    }

    /**
     * @param array $match
     * @param array $except
     *
     * @return mixed
     */
    public function get($match = [], $except = [])
    {
        foreach ($this->repositories as $repository) {

            foreach ($repository->get($match, $except) as $route) {
                $this->routes->push($route);
            }
        }

        return $this->routes;
    }
}
