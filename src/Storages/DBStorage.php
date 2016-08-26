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
        $diff = $data->onlyDiff();
        $toDelete = $data->onlyToDelete();

        $this->delete($toDelete);

        $this->updateOrInsert($diff);
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
     * @param RequestEntity[]|RequestCollection $batch
     */
    private function updateOrInsert($batch)
    {
        foreach ($batch as $request) {
            $this->table()->updateOrInsert([
                'id' => $request->getId(),
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
}