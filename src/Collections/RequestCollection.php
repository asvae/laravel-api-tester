<?php

namespace Asvae\ApiTester\Collections;

use Asvae\ApiTester\Entities\RequestEntity;
use Illuminate\Support\Collection;

/**
 * Class RequestCollection
 *
 * @package \Asvae\ApiTester
 */
class RequestCollection extends Collection
{
    /**
     * Find specific request by passed identificator.
     *
     * @param string $id
     *
     * @return \Asvae\ApiTester\Entities\RequestEntity|null
     */
    public function find($id)
    {
        return $this->offsetExists($id) ? $this->offsetGet($id) : null;
    }

    /**
     * Put new RequestEntity to collection.
     *
     * @param \Asvae\ApiTester\Entities\RequestEntity $request
     *
     * @return \Asvae\ApiTester\Entities\RequestEntity
     */
    public function insert(RequestEntity $request)
    {
        $this->put($request->getId(), $request);

        return $request;
    }

    /**
     * Load data to collection.
     *
     * @param $data
     */
    public function load($data)
    {
        foreach ($data as $row) {
            $this->put($row['id'], RequestEntity::createExisting($row));
        }
    }
}
