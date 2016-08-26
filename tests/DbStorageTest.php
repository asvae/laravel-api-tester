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
    protected $db;

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

        $pdo = new PDO(
            'sqlite::memory:', null, null, array(PDO::ATTR_PERSISTENT => true)
        );
        $this->db = new SQLiteConnection($pdo, 'test');
        $this->collection = new RequestCollection();
        $this->db->setSchemaGrammar(new SQLiteGrammar());
        $builder = new MySqlBuilder($this->db);
        $builder->dropIfExists('requests');
        $builder->create('requests', function (Blueprint $table){
            $table->string('id');
            $table->json('content');
            $table->primary('id');
        });

        $this->storage = new DBStorage(new RequestCollection(), $this->db, 'requests');
    }

    public function testStoringAndGettingData(){
        $id = str_random();

        $reference = ['path' => 'test'];

        $req = new RequestEntity($reference);

        $req->setId($id);

        $this->collection->insert($req);

        $this->storage->put($this->collection);

        $request = $this->storage->get()->first();

        $expected = $reference;

        $expected['id'] = $id;

        $this->assertEquals($expected, $request->toArray());

        $this->collection->find($id)->markToDelete();

        $this->storage->put($this->collection);

        $result = $this->storage->get();

        $this->assertEquals(0, $result->count());
    }
}