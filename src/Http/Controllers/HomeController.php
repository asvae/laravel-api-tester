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
