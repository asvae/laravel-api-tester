<?php

namespace Asvae\ApiTester\Entities;

/**
 * Class Request
 *
 * @package \Asvae\ApiTester\Entities
 */
class RequestEntity extends BaseEntity
{
    /**
     * @var bool
     */
    protected $exists = false;

    /**
     * @var array
     */
    protected $fillable = ['method', 'path', 'headers', 'body', 'name'];

    /**
     * @var bool
     */
    protected $toDelete = false;


    /**
     * @var bool
     */
    protected $dirty;

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

    public function exists()
    {
        return $this->exists;
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
        $newRequest->setExists(true);

        return $newRequest;
    }

    public function setExists($bool)
    {
        $this->exists = $bool;
    }

    public function markedToDelete()
    {
        return $this->toDelete;
    }

    public function setDirty($bool = true)
    {
        $this->dirty = $bool;
    }

    public function isDirty()
    {
        return $this->dirty;
    }

    public function update($data)
    {
        $this->setDirty();
        $this->fill($data);
    }

    public function markToDelete($bool = true)
    {
        $this->toDelete = $bool;
    }

    public function notExists()
    {
        return !$this->exists();
    }

    public function notMarkedToDelete()
    {
        return !$this->markedToDelete();
    }
}
