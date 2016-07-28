<?php

namespace Asvae\ApiTester\Contracts;

use Asvae\ApiTester\Entities\RequestEntity;

interface RequestRepositoryInterface
{
    /**
     * @param $id
     *
     * @return \Asvae\ApiTester\Entities\RequestEntity
     */
    public function find($id);

    /**
     * @param \Asvae\ApiTester\Entities\RequestEntity $request
     *
     * @return void
     */
    public function persist(RequestEntity $request);

    /**
     * @param $id
     *
     * @return bool
     */
    public function exists($id);

    /**
     * @return \Asvae\ApiTester\Collections\RequestCollection|\Asvae\ApiTester\Entities\RequestEntity[]
     */
    public function all();

    /**
     * @return void
     */
    public function flush();

    /**
     * @param \Asvae\ApiTester\Entities\RequestEntity $request
     */
    public function remove(RequestEntity $request);

}
