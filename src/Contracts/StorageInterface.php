<?php

namespace Asvae\ApiTester\Contracts;

/**
 * Class StorageInterface
 *
 * @package \Asvae\ApiTester\Contracts
 */
interface StorageInterface
{
    public function get();

    public function put($data);
}
