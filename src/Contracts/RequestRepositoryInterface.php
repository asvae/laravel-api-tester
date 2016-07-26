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
     * @return mixed
     */
    public function update($data, $id);

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
