<?php
use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Entities\RequestEntity;

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
        $this->requests->load($this->generateData());

        $request = $this->requests->first();

        $test = $this->requests->find($request['id']);

        $this->assertTrue($request === $test);
    }

    /**
     * Check RequestCollection::insert() method
     */
    public function testInsert()
    {
        $this->requests->load($this->generateData());

        $testData = [
                        'path'    => 'test/test',
                        'headers' => ['X-Example' => 'example']
                    ] + $this->sample;

        $request = new RequestEntity($testData);

        $result = $this->requests->insert($request);

        $test = $this->requests->where('id', $request['id'])->first();

        $this->assertEquals($result, $request);
        $this->assertEquals($request, $test);
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
}
