<?php

namespace Asvae\ApiTester\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class StorageInterface
 *
 * @package \Asvae\ApiTester\Contracts
 */
interface StorageInterface
{

    /**
     * Get data from resource.
     *
     * @return array
     */

    public function get();

    /**
     * Put data to resource.
     *
     * @param array|Arrayable[]|Jsonable[]|\Traversable $data
     *
     * @return mixed
     */
    public function put($data);
}
