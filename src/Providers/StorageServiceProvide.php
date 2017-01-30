<?php
/**
 * Created by PhpStorm.
 * User: Greabock
 * Date: 10.08.2016
 * Time: 18:54
 */

namespace Asvae\ApiTester\Providers;


use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Storages\FireBaseStorage;
use Asvae\ApiTester\Storages\JsonStorage;
use Firebase\Token\TokenGenerator;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Exception;

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
            $selectedDriver = config('api-tester.storage_driver');
            $driverClassName = config('api-tester.storage_drivers')[$selectedDriver];
            $requestCollection = $app->make(RequestCollection::class);

            if ($selectedDriver === 'firebase'){
                $tokenGenerator = $app->make(TokenGenerator::class);
                $base = config('api-tester.storage_drivers.firebase.options.base');

                return new FireBaseStorage($requestCollection, $tokenGenerator, $base);
            }

            if ($selectedDriver === 'file'){
                $fileSystem = $app->make(Filesystem::class);
                $path = config('api-tester.storage_drivers.file.options.path');

                return new JsonStorage($fileSystem, $requestCollection, $path);
            }

            throw new Exception("Driver $selectedDriver doesn't exist. Use either 'firebase' or 'file'.");
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