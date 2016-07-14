<?php

namespace Asvae\ApiTester\Http\Controllers;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiTesterController extends Controller
{
    public function index()
    {
        return view('api-tester::api-tester');
    }

    public function routes()
    {
        if (! config('app.debug')) {
            abort(404);
        }

        $routes = [];
        foreach (\Route::getRoutes() as $route) {
            /** @var \Illuminate\Routing\Route $route */
            $routes[] = [
                'method' => $route->getMethods()[0],
                'path'   => $route->getPath(),
                'action' => $route->getActionName(),
            ];
        }

        return response()->json(['data' => $routes]);
    }

    public function assets($file = '')
    {

        $contents = file_get_contents(__DIR__.'/../../resources/assets/build/'.$file);

        if (! preg_match('%^([a-z_\-\.]+?)$%', $file)) {
            abort(404);
        }

        $response = new Response($contents, 200,
            ['Content-Type' => $this->getFileContentType($file)]);

        $response->setSharedMaxAge(31536000);
        $response->setMaxAge(31536000);
        $response->setExpires(new \DateTime('+1 year'));

        return $response;
    }

    protected function getFileContentType($file)
    {
        $array = explode('.', $file);
        $ext = end($array);

        $contentTypes = [
            'css' => 'text/css',
            'js'  => 'text/javascript',
            'svg' => 'image/svg+xml',
        ];

        return $contentTypes[$ext];
    }

    public function testRoutes($type)
    {
        switch ($type) {
            case 'abort':
                abort(418);
            case 'static':
                return 'some static';
            case 'json':
                return response()->json(['i', 'am', 'json']);
        }
    }
}