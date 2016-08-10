<?php

namespace Asvae\ApiTester\Storages;


use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Entities\RequestEntity;
use Asvae\Exceptions\FireBaseException;
use Firebase\FirebaseLib;
use Firebase\Token\TokenGenerator;
use Mockery\CountValidator\Exception;
use Symfony\Component\Debug\Exception\ClassNotFoundException;

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
     * @var TokenGenerator
     */
    protected $tokens;

    /**
     * FireBaseStorage constructor.
     * @param RequestCollection $collection
     * @param TokenGenerator $tokens
     * @param $base
     */
    public function __construct(RequestCollection $collection, TokenGenerator $tokens, $base)
    {
        $this->collection = $collection;
        $this->initFirebase($tokens, $base);
    }

    /**
     * Get data from resource.
     *
     * @return RequestCollection
     */
    public function get()
    {
        $result = $this->getFromFireBase();

        $data = $this->addHeadersIfEmpty($result);

        return $this->makeCollection($data);
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

        if ($result && is_string($result)) {
            throw new FireBaseException($result);
        }

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

    private function initFirebase(TokenGenerator $tokens, $base)
    {
        $this->firebase = new FirebaseLib($base, $tokens->create());
    }

    private function addHeadersIfEmpty($data)
    {
        foreach ($data as $key => $request) {
            if (!array_key_exists('headers', $request)) {
                $data[$key]['headers'] = [];
            }
        }

        return $data;
    }
}