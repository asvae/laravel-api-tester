<?php

namespace Asvae\ApiTester\Http\Controllers;

use Asvae\ApiTester\Contracts\RequestRepositoryInterface;
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
     * @param \Asvae\ApiTester\Http\Requests\StoreRequest $request
     *
     * @return int
     */
    public function store(StoreRequest $request)
    {
        $data = $this->repository->store($request->only([
            'path',
            'name',


            'method',
            'params',
            'headers',
            'body'
        ]));

        return response(compact('data'), 201);
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return response(null, 204);
    }

    /**
     * @param \Asvae\ApiTester\Http\Requests\UpdateRequest $request
     * @param  int                                         $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        if (!$this->repository->exists($id)) {
            return response("not found", 404);
        }

        $data = $this->repository->update($request->all(), $id);

        return response(compact('data'));
    }
}
