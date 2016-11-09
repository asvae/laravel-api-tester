<?php
/**
 * Created by PhpStorm.
 * User: Greabock
 * Date: 10.08.2016
 * Time: 18:54
 */

namespace Asvae\ApiTester\Providers;

use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Storages\DBStorage;
use Asvae\ApiTester\Storages\FireBaseStorage;
use Asvae\ApiTester\Storages\JsonStorage;
use Firebase\Token\TokenGenerator;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvide extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register required storage service from list of available.
        $this->app->singleton(StorageInterface::class,
            function (Container $app) {

//            $ref = new \ReflectionClass($app);
//
//            $insts = $ref->getProperty('instances');
//            $insts->setAccessible(true);
//            $insts = $insts->getValue($app);
//
//            dump(array_keys($insts));
//
//            exit();

                // Defined driver
                $driver = config('api-tester.storage_drivers')[config('api-tester.storage_driver')];

                return $app->make($driver['class'], $driver['options']);
            });

        // Register token-generator. It's bound to key and not to class
        // to prevent conflict with user defined token generator.
        $this->app->singleton('api-tester.token_generator',
            function (Container $app) {
                $config = $app['config']['api-tester.storage_drivers.firebase.token'];

                return (new TokenGenerator($config['secret']))->setOptions($config['options'])
                    ->setData($config['data']);
            });

        $this->app->singleton('api-tester.db_connection',
            function (Container $app) {
                $connectionName = config('api-tester.storage_drivers.database.connection');

                return $app['db']->connection($connectionName);
            });

        // Pass generator into storage.
        $this->app->when(FireBaseStorage::class)
            ->needs(TokenGenerator::class)
            ->give('api-tester.token_generator');

        // Pass connection into storage.
        $this->app->when(DBStorage::class)
            ->needs('db.connection')
            ->give('api-tester.db_connection');
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
            'api-tester.db_connection',
            StorageInterface::class,
        ];
    }
}