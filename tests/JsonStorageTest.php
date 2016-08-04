<?php
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
    protected $dir;

    /**
     * @type string
     */
    protected $filename;

    /**
     * @type array
     */
    protected $referenceData;

    /**
     * @type string
     */
    protected $referenceContent;

    public function setUp()
    {
        $this->dir = __DIR__ . '/tmp';
        $this->filename = 'test.db';

        $this->referenceData = [
            ['some_data' => 1],
            ['other_data' => 2],
            ["one_more_data\n"]
        ];

        $this->referenceContent = "{\"some_data\":1}\n{\"other_data\":2}\n[\"one_more_data\\n\"]\n";

        $fs = new Filesystem;

        if ($fs->isDirectory($this->dir)) {
            $fs->deleteDirectory($this->dir);
        }

        $this->storage = new JsonStorage(
            $fs,
            $this->dir,
            $this->filename
        );
    }

    public function testStoringData()
    {
        $this->storage->put($this->referenceData);

        $testRow = file_get_contents($this->dir . DIRECTORY_SEPARATOR . $this->filename);

        $this->assertEquals($this->referenceContent, $testRow);
    }

    public function testGettingData()
    {
        mkdir($this->dir, 0755, true);

        file_put_contents($this->dir . DIRECTORY_SEPARATOR . $this->filename, $this->referenceContent);

        $data = $this->storage->get();

        $this->assertEquals($this->referenceData, $data);
    }
    
    public function testRemoveAll()
    {
        $this->storage->put([]);
        $data = $this->storage->get();

        $this->assertEquals([], $data);
    }
}
