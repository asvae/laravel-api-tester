<?php

namespace Asvae\ApiTester\Repositories;

use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Contracts\RequestRepositoryInterface;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\JsonStorage;
use Illuminate\Filesystem\Filesystem;


/**
 * Class DefaultRequestRepository
 *
 * @package \Asvae\ApiTester
 */
class RequestRepository implements RequestRepositoryInterface
{
    /**
     * @type \Asvae\ApiTester\Contracts\StorageInterface
     */
    protected $storage;

    /**
     * @type \Asvae\ApiTester\Collections\RequestCollection
     */
    protected $requests;

    public function __construct(RequestCollection $collection, StorageInterface $storage)
    {
        $this->storage = $storage;
        $this->requests = $collection;

        $this->load($storage->get());
    }

    /**
     * @return \Asvae\ApiTester\Collections\RequestCollection
     */
    public function all()
    {
        return $this->requests->all();
    }

    /**
     * Add new request
     *
     * @param array $data
     *
     * @return array
     */
    public function store(array $data)
    {
        $request = $this->requests->insert($data);

        return $request;
    }

    /**
     * Update request with given id
     *
     * @param array $id
     * @param       $data
     * @return array
     */
    public function update($id, $data)
    {
        $request = $this->requests->update($id, $data);

        return $request;
    }

    /**
     * Check request with given id exists
     *
     * @param int $id
     *
     * @return bool
     */
    public function exists($id)
    {
        return ! $this->requests->where('id', (int) $id)->isEmpty();
    }

    /**
     * Get request by id
     *
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        return $this->requests->where('id', (string) $id)->first();
    }

    /**
     * Flush collection to file
     */
    public function flush()
    {
        $this->storage->put($this->requests);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $this->requests->delete($id);
    }

    /**
     * Load data to collection from JSON file
     *
     * @param $data
     */
    private function load($data)
    {
        $this->requests->load($data);
    }
}
