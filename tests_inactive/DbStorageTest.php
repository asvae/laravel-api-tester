<?php

use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Entities\RequestEntity;
use Asvae\ApiTester\Storages\DBStorage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Database\Schema\MySqlBuilder;
use Illuminate\Database\SQLiteConnection;

class DbStorageTest extends TestCase
{
    /**
     * @var DBStorage
     */
    protected $storage;

    /**
     * @var array
     */
    protected $referenceData;

    /**
     * @var RequestCollection
     */
    protected $collection;

    public function setUp(){

        $this->storage = $this->setUpStorage();

        $this->collection = new RequestCollection();
    }

    private function setUpStorage()
    {
        $pdo = new PDO(
            'sqlite::memory:', null, null, array(PDO::ATTR_PERSISTENT => true)
        );

        $db = new SQLiteConnection($pdo, 'test');

        $db->setSchemaGrammar(new SQLiteGrammar());

        $this->presetDataBase($db);

        return new DBStorage(new RequestCollection(), $db, 'requests');
    }

    private function presetDataBase($db)
    {
        $builder = new MySqlBuilder($db);

        $builder->dropIfExists('requests');

        $builder->create('requests', function (Blueprint $table){
            $table->string('id');
            $table->json('content');
            $table->primary('id');
        });


    }

    public function testStoringAndGettingData(){


        $reference = ['path' => 'test'];

        $expected = $reference;
        $id = str_random();
        $expected['id'] = $id;

        //Put data
        $req = new RequestEntity($reference);
        $req->setId($id);
        $this->collection->insert($req);
        $this->storage->put($this->collection);
        $request = $this->storage->get()->first();
        $this->assertEquals($expected, $request->toArray());

        // Update data
        $this->collection = $this->storage->get();
        $request = $this->collection->find($id);
        $request->update(['path' => 'test2']);
        $this->storage->put($this->collection);
        $this->collection = $this->storage->get();
        $request = $this->collection->find($id);
        $this->assertEquals(['id'=> $id, 'path' => 'test2'], $request->toArray());
        
        //Delete data
        $this->collection->find($id)->markToDelete();
        $this->storage->put($this->collection);
        $this->collection = $this->storage->get();
        $this->assertEquals(0, $this->collection->count());
    }

}