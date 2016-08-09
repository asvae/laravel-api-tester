<?php
use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Entities\RequestEntity;
use Asvae\ApiTester\Storages\JsonStorage;
use Illuminate\Filesystem\Filesystem;

/**
 * Class TestJsonStorage
 *
 * @package \\${NAMESPACE}
 */
class JsonStorageTest extends TestCase
{
    /**
     * @type \Asvae\ApiTester\Storages\JsonStorage
     */
    protected $storage;

    /**
     * @type string
     */
    protected $storageFilePath;

    /**
     * @type array
     */
    protected $referenceData;

    /**
     * @type string
     */
    protected $referenceContent;

    /**
     * @type string
     */
    protected $dir;

    /**
     * @var RequestCollection
     */
    protected $referenceCollection;

    /**
     *
     */
    public function setUp()
    {
        $this->dir = __DIR__ . '/tmp';
        $this->storageFilePath = $this->dir . '/test.db';

        $this->referenceData = [
            ['id'=> '111', 'path' => 'aaaa'],
            ['id'=> '222', 'path' => 'bbbb'],
            ['id'=> '333', 'path' => 'cccc'],
            ['id'=> '444', 'path' => 'dddd'],
            ['id'=> '555', 'path' => 'eeee'],
        ];

        $this->referenceCollection = (new RequestCollection())->load($this->referenceData);

        $this->referenceContent = "{\"path\":\"aaaa\",\"id\":\"111\"}\n{\"path\":\"bbbb\",\"id\":\"222\"}\n{\"path\":\"cccc\",\"id\":\"333\"}\n{\"path\":\"dddd\",\"id\":\"444\"}\n{\"path\":\"eeee\",\"id\":\"555\"}\n";

        $fs = new Filesystem;

        if ($fs->isDirectory($this->dir)) {
            $fs->deleteDirectory($this->dir);
        }

        $this->storage = new JsonStorage(
            $fs,
            new RequestCollection(),
            $this->storageFilePath
        );
    }

    public function testStoringData()
    {
        $this->referenceCollection->each(function(RequestEntity $value){
            $value->setDirty(); $value->exists();
        });

        $this->storage->put($this->referenceCollection);

        $testRow = file_get_contents($this->storageFilePath);

        $this->assertEquals($this->referenceContent, $testRow);
    }

    public function testGettingData()
    {
        mkdir($this->dir, 0755, true);

        file_put_contents($this->storageFilePath, $this->referenceContent);

        $data = $this->storage->get();

        $this->assertEquals($this->referenceCollection, $data);
    }

    public function testRemoveAll()
    {
        $this->storage->put(new RequestCollection());
        $data = $this->storage->get();

        $this->assertEquals(new RequestCollection(), $data);
    }
}
