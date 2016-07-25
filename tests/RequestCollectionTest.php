<?php
use Asvae\ApiTester\Collections\RequestCollection;

/**
 * Class RequestCollectionTest
 *
 * @package \\${NAMESPACE}
 */
class RequestCollectionTest extends TestCase
{

    /**
     * @type RequestCollection
     */
    protected $requests;

    /**
     * @type
     */
    protected $sample;

    /**
     * @type int
     */
    protected $requestsCount;

    public function setUp()
    {
        $this->requests = new RequestCollection();

        $this->requests;

        $this->requestsCount = 10;

        $this->sample = [
            'path'    => 'example/{param}',
            'headers' => [],
            'body'    => [],
        ];
    }

    /**
     * Check RequestCollection::load() method
     */
    public function testLoad()
    {
        $this->requests->load($this->generateData());

        $this->assertEquals(10, $this->requests->count());
        $this->assertEquals(10, $this->requests->max('id'));
        $this->assertEquals(55, $this->requests->sum('id'));
    }

    /**
     * Check RequestCollection::find() method
     */
    public function testFind()
    {
        $requests = $this->requests->make($this->generateData());

        $id = $this->getRandomId();
        $request = $requests->find($id);
        $this->assertEquals($request['path'], str_replace('{param}', $id, $this->sample['path']));
    }

    /**
     * Check RequestCollection::update() method
     */
    public function testUpdate()
    {
        $id = $this->getRandomId();
        $requests = $this->requests->make($this->generateData());

        $testData = [
            'path'    => 'test/test',
            'headers' => ['X-Example' => 'example']
        ] + $this->sample;

        $requests->update($testData, $id);

        $request = $requests->where('id', $id)->first();

        $this->assertEquals($request['id'], $id);

        $this->assertEquals($request['path'], $testData['path']);
        $this->assertEquals($request['headers'], $testData['headers']);
        $this->assertEquals($request['body'], $testData['body']);
    }

    /**
     * Check RequestCollection::insert() method
     */
    public function testInsert()
    {
        $requests = $this->requests->make($this->generateData());

        $testData = [
            'path'    => 'test/test',
            'headers' => ['X-Example' => 'example']
        ] + $this->sample;

        $expectedId = $requests->max('id') + 1;

        $result = $requests->insert($testData);

        $this->assertEquals($expectedId, $result['id']);
        $this->assertEquals($testData['path'], $result['path']);
        $this->assertEquals($testData['headers'], $result['headers']);
        $this->assertEquals($testData['body'], $result['body']);

        $request = $requests->where('id', $expectedId)->first();

        $this->assertEquals($request, $result);
    }

    public function testDelete(){
        $requests = $this->requests->make($this->generateData());
        $id = $this->getRandomId();

        $requests->delete($id);

        $this->assertEquals(true, $requests->where('id', $id)->isEmpty());
        $this->assertEquals($this->requestsCount -1, $requests->count());
    }

    private function generateData()
    {
        $data = [];

        for ($i = 1; $i <= $this->requestsCount; $i++) {

            $request = [
                'id'   => $i,
                'path' => str_replace('{param}', $i, $this->sample['path']),
            ];

            $data[] = $request + $this->sample;
        }

        return $data;
    }

    private function getRandomId()
    {
        return random_int(1, $this->requestsCount);
    }
}
