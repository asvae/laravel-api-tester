<?php

namespace Asvae\ApiTester\Storages;


use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Entities\RequestEntity;
use Firebase\FirebaseLib;
use Firebase\Token\TokenGenerator;

class FireBaseStorage implements StorageInterface
{
    /**
     * @var FirebaseLib
     */
    protected $firebase;

    /**
     * @var RequestCollection
     */
    protected $collection;

    /**
     * FireBaseStorage constructor.
     * @param RequestCollection $collection
     * @param $base
     * @param $secret
     */
    public function __construct(RequestCollection $collection, $base, $secret)
    {
        $this->collection = $collection;

        $this->initFirebase($base, $secret);

    }

    /**
     * Get data from resource.
     *
     * @return RequestCollection
     */
    public function get()
    {
        return $this->makeCollection($this->getFromFireBase());
    }

    /**
     * Put data to resource.
     *
     * @param $data RequestCollection
     * @return void
     */
    public function put(RequestCollection $data)
    {
        $this->store($data->onlyDiff());
        $this->delete($data->onlyToDelete());
    }

    /**
     * @param $data
     */
    private function store($data)
    {
        foreach ($data as $request) {
            $this->putToFireBase($request);
        }
    }


    /**
     * @param RequestCollection $data
     */
    private function delete(RequestCollection $data)
    {
        foreach ($data as $request) {
            $this->deleteFromFirebase($request);
        }
    }

    /**
     * @param RequestEntity $request
     */
    private function putToFireBase(RequestEntity $request)
    {
        $this->firebase->set('requests/' . $request->getId(), $request->jsonSerialize());
    }


    /**
     * @param RequestEntity $request
     */
    private function deleteFromFirebase(RequestEntity $request)
    {
        $this->firebase->delete('requests/' . $request->getId());
    }

    /**
     * @return array
     */
    private function getFromFireBase()
    {
        $result = json_decode($this->firebase->get('requests'), true);

        return $result ?: [];
    }

    /**
     * @param array $data
     * @return RequestCollection
     */
    protected function makeCollection($data = [])
    {
        return $this->collection->make()->load($data);
    }

    private function initFirebase($base, $secret)
    {
        $token = (new TokenGenerator($secret))
            ->setOption('admin', true)->create();

        $firebase = new FirebaseLib($base, $token);

        $this->firebase = $firebase;
    }
}