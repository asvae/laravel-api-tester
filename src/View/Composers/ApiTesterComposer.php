<?php
/**
 * Created by PhpStorm.
 * User: Greabock
 * Date: 10.08.2016
 * Time: 21:20
 */

namespace Asvae\ApiTester\View\Composers;


use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\View;

class ApiTesterComposer
{
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->driver = $app['config']['api-tester']['storage_driver'];

    }

    public function compose(View $view)
    {
        $firebaseToken = null;
        if($this->driver  === 'firebase'){

            $firebaseToken = $this->app['api-tester.token_generator']->create();
        }

        $view->with(compact('firebaseToken'));
    }
}