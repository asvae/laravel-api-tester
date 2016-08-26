<?php

namespace Asvae\ApiTester\Storages;


use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Contracts\StorageInterface;
use Asvae\ApiTester\Entities\RequestEntity;
use Illuminate\Database\ConnectionInterface;


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
     * @param ConnectionInterface $connection
     * @param $table
     */
    public function __construct(RequestCollection $collection, ConnectionInterface $connection, $table)
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
        $toInsert = $data->onlyDiff()->onlyNotExists();
        $toUpdate = $data->onlyDiff()->onlyExists();
        $toDelete = $data->onlyToDelete();

        $this->delete($toDelete);

        $this->insert($toInsert);
        $this->update($toUpdate);
    }

    /**
     * @return mixed
     */
    private function getAll()
    {
        $requests = [];
        foreach ($this->getResult() as $row){
            $requests[] = json_decode($row->content, true);
        }

        return $requests;
    }

    private function makeCollection($rows)
    {
        return $this->collection->make()->load($rows);
    }

    /**
     * @param RequestEntity[]|RequestCollection $toUpdate
     */
    private function update($toUpdate)
    {
        foreach ($toUpdate as $request) {
            $this->table()->where('id', $request->getId())->update([
                'content' => $request->toJson()
            ]);
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

    private function getResult()
    {
        return $this->table()->get();
    }

    /**
     * @param RequestEntity[]|RequestCollection $toInsert
     */
    private function insert($toInsert)
    {
        foreach ($toInsert as $request) {
            $this->table()->insert([
                'id' => $request->getId(),
                'content' => $request->toJson()
            ]);
        }
    }
}