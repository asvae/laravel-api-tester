<?php

namespace Asvae\ApiTester\Http\Controllers;

use Asvae\ApiTester\Contracts\RequestRepositoryInterface;
use Asvae\ApiTester\Entities\RequestEntity;
use Asvae\ApiTester\Http\Requests\StoreRequest;
use Asvae\ApiTester\Http\Requests\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class RequestController
 *
 * @package \Asvae\ApiTester\Http\Controllers
 */
class RequestController extends Controller
{
    /**
     * @type \Asvae\ApiTester\Contracts\RequestRepositoryInterface
     */
    protected $repository;

    /**
     * RequestController constructor.
     *
     * @param \Asvae\ApiTester\Contracts\RequestRepositoryInterface $repository
     */
    public function __construct(RequestRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $data = $this->repository->all();

        return response(compact('data'), 200);
    }

    /**
     *
     * @param \Asvae\ApiTester\Http\Requests\StoreRequest $storeRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $storeRequest)
    {
        $request = new RequestEntity($storeRequest->all());

        $this->repository->persist($request);

        $this->repository->flush();

        return response(['data' => $request->toArray()], 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        if (! $this->repository->exists($id)) {
            return response(null, 404);
        }

        $this->repository->remove($id);

        $this->repository->flush();

        return response(null, 204);
    }

    /**
     * @param \Asvae\ApiTester\Http\Requests\UpdateRequest $request
     * @param  string                                      $request
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     *
     */
    public function update(UpdateRequest $request)
    {
        // TODO What's happening in here?

        $requestEntity = $this->repository->find($request->id);

        if (!$requestEntity instanceof RequestEntity) {
            return response(404);
        }

        $requestEntity->update($request->all());

        $this->repository->flush();

        return response(['data' => $requestEntity->toArray()], 200);
    }
}
