<?php
/**
 * Created by PhpStorm.
 * User: Greabock
 * Date: 10.08.2016
 * Time: 18:54
 */

namespace Asvae\ApiTester\Providers;


use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Storages\FireBaseStorage;
use Firebase\Token\TokenGenerator;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvide extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Регистрируем конкретный сторэйдж из списка доступных.
        $this->app->singleton(StorageInterface::class, function (Container $app) {
            // Defined driver
            $driver = config('api-tester.storage_drivers')[config('api-tester.storage_driver')];

            return $app->make($driver['class'], $driver['options']);
        });

        // Регистрация токен-генератора. Привязывается к ключу а не классу,
        // чтобы не конфликтовать с пользовательским генератором токенов.
        $this->app->singleton('api-tester.token_generator', function (Container $app) {
            $config = $app['config']['api-tester.storage_drivers.firebase.token'];
            return (new TokenGenerator($config['secret']))
                ->setOptions($config['options'])
                ->setData($config['data']);
        });

        // Подсовываем генератор в сторэйдж
        $this->app
            ->when(FireBaseStorage::class)
            ->needs(TokenGenerator::class)
            ->give('api-tester.token_generator');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'api-tester.token_generator',
            StorageInterface::class,
        ];
    }
}