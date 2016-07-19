<?php

namespace Asvae\ApiTester\Contracts;

interface RouteRepositoryInterface
{
    /**
     * @param array $match
     * @param array $except
     *
     * @return mixed
     */
    public function get($match = [], $except = []);
}
