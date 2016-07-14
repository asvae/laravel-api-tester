<?php

namespace Asvae\ApiTester;

use Asvae\ApiTester\Providers\RouteServiceProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
