<?php
use Asvae\ApiTester\Collections\RequestCollection;
use Asvae\ApiTester\Entities\RequestEntity;
use Mockery as m;

class RequestCollectionTest extends TestCase
{
    /**
     * @type RequestCollection
     */
    protected $requests;

    /**
     * @type array
     */
    protected $exampleRequest;

    /**
     * @type int
     */
    protected $requestsCount;

    public function setUp()
    {
        $this->requests = new RequestCollection();
        $this->requestsCount = 10;
        $this->exampleRequest = [
            'path'    => 'example/{param}',
            'headers' => [],
            'body'    => [],
        ];
    }

    /**
     * @covers Asvae\ApiTester\Collections\RequestCollection::load()
     */
    public function testLoad()
    {
        $this->assertEquals(0, $this->requests->count());

        $this->requests->load($this->generateData());

        $this->assertEquals(10, $this->requests->count());
    }

    /**
     * @covers Asvae\ApiTester\Collections\RequestCollection::find()
     */
    public function testFind()
    {
        $this->hydrateRequest();

        $request = $this->requests->first();
        $foundRequest = $this->requests->find($request['id']);

        $this->assertTrue($request === $foundRequest);
    }

    /**
     * @covers Asvae\ApiTester\Collections\RequestCollection::insert()
     */
    public function testInsert()
    {
        $this->hydrateRequest();
        $request = m::mock(RequestEntity::class)->shouldReceive([
                'getId'        => 5,
                'offsetGet'    => 5,
                'offsetExists' => true,
            ])->andSet('id', 5)->mock();

        $this->requests->insert($request);
        $foundRequest = $this->requests->where('id', $request['id'])->first();

        $this->assertEquals($request, $foundRequest);
    }

    private function hydrateRequest()
    {
        $this->requests->load($this->generateData());
    }

    private function generateData()
    {
        $data = [];

        for ($i = 1; $i <= $this->requestsCount; $i++) {

            $request = [
                'id'   => $i,
                'path' => str_replace('{param}', $i,
                    $this->exampleRequest['path']),
            ];

            $data[] = $request + $this->exampleRequest;
        }

        return $data;
    }
}
