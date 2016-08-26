<?php

namespace Asvae\ApiTester\Storages;


use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Entities\RequestEntity;
use Illuminate\Database\Connection;

class DBStorage implements StorageInterface
{
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var RequestCollection
     */
    protected $collection;

    /**
     * DBStorage constructor.
     * @param RequestCollection $collection
     * @param Connection $connection
     * @param $table
     */
    public function __construct(RequestCollection $collection, Connection $connection, $table)
    {
        $this->collection = $collection;
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * Get data from resource.
     *
     * @return RequestCollection
     */
    public function get()
    {
        return $this->makeCollection($this->getAll());
    }

    /**
     * Put data to resource.
     *
     * @param $data RequestCollection
     * @return void
     */
    public function put(RequestCollection $data)
    {
        $toUpdate = $data->onlyExists()->onlyDiff();
        $toDelete = $data->onlyToDelete();
        $toInsert = $data->onlyNotExists()->onlyNotMarkedToDelete();

        $this->update($toUpdate);
        $this->delete($toDelete);
        $this->insert($toInsert);
    }

    /**
     * @return mixed
     */
    private function getAll()
    {
        return $this->table()->get();
    }

    private function makeCollection($rows)
    {
        return $this->collection->load($rows);
    }

    /**
     * @param RequestEntity[]|RequestCollection $toUpdate
     */
    private function update($toUpdate)
    {
        foreach ($toUpdate as $request){
            $this->table()
                ->where('id', $request->getId())
                ->update($request->toArray());
        }
    }

    private function table()
    {
        return $this->connection->table($this->table);
    }

    /**
     * @param RequestEntity[]|RequestCollection $toDelete
     */
    private function delete($toDelete)
    {
        foreach ($toDelete as $request) {
            $this->table()->where('id', $request->getId())->delete();
        }
    }

    /**
     * @param RequestEntity[]|RequestCollection $toInsert
     */
    private function insert($toInsert)
    {
        $this->table()->insert($toInsert->toArray());
    }
}