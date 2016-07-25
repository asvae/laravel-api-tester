<?php

namespace Asvae\ApiTester\Contracts;

interface RequestRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function get($id);

    /**
     * @param $data
     *
     * @return array
     */
    public function store(array $data);

    /**
     * @param $id
     *
     * @param $data
     *
     */
    public function update($id, $data);

    /**
     * @param int $id
     *
     * @return bool
     */
    public function exists($id);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $id
     */
    public function delete($id);
}
