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

    public function testScopes(){
        $this->hydrateRequest();
        $count = $this->requests->count();

        $request = $this->requests->first();
        $request->update(['path' => 'new/path']);
        $this->assertEquals(1, $this->requests->onlyDiff()->count());
        $this->assertEquals(1, $this->requests->onlyDirty()->count());
        $this->assertEquals($count, $this->requests->count());
        $this->assertEquals($count, $this->requests->onlyNotMarkedToDelete()->count());
        $this->assertEquals(0, $this->requests->onlyToDelete()->count());

        $this->requests->insert(new RequestEntity(['path' => 'newPath']));
        $this->assertEquals($count, $this->requests->onlyExists()->count());
        $this->assertEquals(1, $this->requests->onlyNotExists()->count());
        $this->assertEquals($count+1, $this->requests->count());
        $this->assertEquals(1, $this->requests->onlyDirty()->count());
        $this->assertEquals(0, $this->requests->onlyToDelete()->count());

        $request = $this->requests->last();
        $request->markToDelete();
        $this->assertEquals(1, $this->requests->onlyToDelete()->count());
        $this->assertEquals($count, $this->requests->onlyExists()->count());
        $this->assertEquals($count, $this->requests->onlyNotMarkedToDelete()->count());
    }

    private function hydrateRequest()
    {
        $this->requests = $this->requests->make()->load($this->generateData());
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
