<?php

namespace Asvae\ApiTester\Collections;

use Illuminate\Support\Collection;

/**
 * Class RequestCollection
 *
 * TODO: Clear, optimize.
 *
 * @package \Asvae\ApiTester
 */
class RequestCollection extends Collection
{
    public function find($id){
        return $this->where('id', (int) $id)->first();
    }

    public function insert($request){
        $id = $this->max('id');
        $request['id'] = ++$id;
        $this->push($request);

        return $request;
    }

    /**
     * @param $request
     * @param $id
     */
    public function update($request, $id)
    {
        $key = $this->where('id', $id, false)->keys()->first();

        $request = $request + $this->where('id', $id, false)->first();

        $this->put($key, $request);

        return $request;
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $keys = [];

        foreach($this as $key => $request){
            if($id === $request['id']){
                $keys[] = $key;
            }
        }

        $this->forget($keys);
    }

    /**
     * @param $data
     */
    public function load($data){
        foreach($data as $request){
            $this->push($request);
        }
    }
}
