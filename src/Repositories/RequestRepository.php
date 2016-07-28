<?php

namespace Asvae\ApiTester\Repositories;

use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Contracts\RequestRepositoryInterface;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Entities\RequestEntity;
use Illuminate\Filesystem\Filesystem;


/**
 * Class DefaultRequestRepository
 *
 * @package \Asvae\ApiTester
 */
class RequestRepository implements RequestRepositoryInterface
{
    /**
     * @type \Asvae\ApiTester\Collections\RequestCollection
     */
    protected $requests;

    /**
     * @type \Asvae\ApiTester\Contracts\StorageInterface
     */
    protected $storage;

    /**
     * RequestRepository constructor.
     *
     * @param \Asvae\ApiTester\Contracts\StorageInterface    $storage
     * @param \Asvae\ApiTester\Collections\RequestCollection $requests
     */
    public function __construct(StorageInterface $storage, RequestCollection $requests)
    {
        $this->storage = $storage;
        $this->requests = $requests;
        $this->load();
    }

    /**
     * Get data from storage and load it into collection.
     * @return void
     */
    protected function load()
    {
        $this->requests->load($this->getDataFromStorage());
    }

    /**
     * Replace existing collection with data loaded from storage.
     */
    protected function reload()
    {
        $this->requests = $this->requests->make($this->getDataFromStorage());
    }

    /**
     * Get all stored data storage.
     *
     * @return mixed
     */
    protected function getDataFromStorage()
    {
        return $this->storage->get();
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function find($id)
    {
        return $this->requests->find($id);
    }

    /**
     * @param \Asvae\ApiTester\Entities\RequestEntity $request
     *
     * @return mixed
     */
    public function persist(RequestEntity $request)
    {
        $request->setId(str_random());
        $this->requests->insert($request);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function exists($id)
    {
        return $this->requests->has($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->requests->values();
    }

    /**
     * @param \Asvae\ApiTester\Entities\RequestEntity $request
     */
    public function remove(RequestEntity $request)
    {
        $this->requests->forget($request->getId());
        $this->flush();
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->storage->put($this->requests);
    }
}
