<?php

namespace Asvae\ApiTester\Entities;

/**
 * Class Request
 *
 * @package \Asvae\ApiTester\Entities
 */
class RequestEntity extends BaseEntity
{
    protected $fillable = ['method', 'path', 'headers', 'body', 'name'];

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }

    /**
     * @return array
     */
    public function getId()
    {
        return $this->attributes['id'];
    }

    /**
     * @param $data
     *
     * @return static
     */
    public static function createExisting($data)
    {
        $newRequest = new static($data);
        $newRequest->setId($data['id']);

        return $newRequest;
    }
}
