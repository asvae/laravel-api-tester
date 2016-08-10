<?php

namespace Asvae\ApiTester\Providers;


use Asvae\ApiTester\View\Composers\ApiTesterComposer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(API_TESTER_PATH . '/resources/views', 'api-tester');
    }

    public function boot(Factory $view)
    {
        $view->composer(['api-tester::api-tester'], ApiTesterComposer::class);
    }
}