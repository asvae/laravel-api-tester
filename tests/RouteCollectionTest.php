<?php

use Asvae\ApiTester\RouteCollection;

/**
 * Class RouteCollectionTest
 * @covers \Asvae\ApiTester\RouteCollection
 */
class RouteCollectionTest extends TestCase
{

    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var array
     */
    private $defaultRoute = [
        'method' => 'GET',
        'action' => '',
        'name'   => null,
        'path'   => 'api-tester',
    ];

    protected function setUp()
    {
        $routes = $this->fakeRoutes([
            [
                'method' => 'GET',
                'path'   => 'user',
            ],
            [
                'method' => 'GET',
                'path'   => 'user/{id}',
            ],
            [
                'method' => 'POST',
                'path'   => 'user',
            ],
            [
                'method' => 'DELETE',
                'path'   => 'user/{id}',
            ],
            [
                'method' => 'GET',
                'path'   => 'article',
            ],
        ]);

        $this->routes = new RouteCollection($routes);
    }

    private function fakeRoutes(array $routes)
    {
        $result = [];

        foreach ($routes as $route) {
            $result[] = array_replace($this->defaultRoute, $route);
        }

        return $result;
    }

    public function testMatchRoute()
    {
        $this->assertEquals(1,
            $this->routes->filterMatch(['article'])->count());
        $this->assertEquals(4, $this->routes->filterMatch(['user'])->count());
    }

    public function testMatchMethod()
    {
        $count = $this->routes->filterMatch([['method' => 'GET']])->count();
        $this->assertEquals(3, $count);
    }

    public function testMatchMethods()
    {
        $count = $this->routes->filterMatch([
            ['method' => 'POST'],
            ['method' => 'DELETE'],
        ])->count();
        $this->assertEquals(2, $count);
    }

    public function testMatchRegex()
    {
        $this->assertEquals(2, $this->routes->filterMatch(['user$',])->count());
    }

    public function testMatchEmptyPattern()
    {
        $this->assertEquals(5, $this->routes->filterMatch([[]])->count());
    }

    public function testExcept()
    {
        $count = $this->routes->filterExcept([
            ['method' => 'POST'],
            ['method' => 'DELETE'],
        ])->count();
        $this->assertEquals(3, $count);
    }
}