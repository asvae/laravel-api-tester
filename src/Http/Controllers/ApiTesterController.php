<?php

namespace Asvae\ApiTester\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Asvae\ApiTester\Contracts\RouteRepositoryInterface;

class ApiTesterController extends Controller
{
    public function __construct()
    {
        if (!config('api-tester.enabled')) {
            abort(403, 'Api tester is disabled.');
        }
    }

    public function index()
    {
        return view('api-tester::api-tester');
    }

    public function routes(RouteRepositoryInterface $repository)
    {
        $data = $repository->get(
            config('api-tester.include'),
            config('api-tester.exclude')
        );

        return response()->json(compact('data'));
    }

    public function testRoutes($type, Request $request)
    {
        switch ($type) {
            case 'abort':
                abort(418);
            case 'static':
                return 'some static';
            case 'json':
                return response()->json(['i', 'am', 'json']);
            case 'request':
                return $request->all();
        }
    }
}
