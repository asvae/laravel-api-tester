<?php

namespace Asvae\ApiTester\Http\Controllers;

use Asvae\ApiTester\Contracts\RouteRepositoryInterface;
use Illuminate\Routing\Controller;

/**
 * Class RoutesController
 *
 * @package \Asvae\ApiTester\Http\Controllers
 */
class RouteController extends Controller
{
    /**
     * Display list of all available routes.
     *
     * @param RouteRepositoryInterface $repository
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RouteRepositoryInterface $repository)
    {
        $data = $repository->get(
            config('api-tester.include'),
            config('api-tester.exclude')
        );

        return response()->json(compact('data'));
    }
}
