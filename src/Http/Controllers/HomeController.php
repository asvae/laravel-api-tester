<?php

namespace Asvae\ApiTester\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('api-tester::api-tester');
    }

    /**
     * Depending on parameter you'll get different response.
     * Here are your options:
     * - abort
     * - static
     * - var_dump
     * - json
     * - request
     *
     * @param         $type
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|string|void
     */
    public function testRoutes($type, Request $request)
    {
        switch ($type) {
            case 'abort':
                abort(418);
            case 'static':
                return 'some static';
            case 'var_dump':
                return var_dump(['some' => 'text']);
            case 'json':
                return response()->json(['i', 'am', 'json']);
            case 'request':
                return $request->all();
        }
    }
}
