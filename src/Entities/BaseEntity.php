<?php

namespace Asvae\ApiTester\Entities;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use JsonSerializable;

/**
 * Class BaseEntity.
 *
 * @package Asvae\ApiTester\Entities
 */
abstract class BaseEntity implements Arrayable, Jsonable, ArrayAccess, JsonSerializable
{
    /**
     * Names of attributes that can be filled.
     *
     * @type array
     */
    protected $fillable = [];

    /**
     * Entity attributes.
     *
     * @type array
     */
    protected $attributes = [];

    /**
     * BaseEntity constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Fill attributes that can be filled.
     *
     * @param $data
     *
     * @return void
     */
    public function fill(array $data)
    {
        $this->attributes = array_merge($this->attributes, $this->filterFillable($data));
    }

    /**
     * Get the instance as an array.
     *
     * @return array return array representation of object.
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Return array with with key that can be filled.
     *
     * @param array $data
     *
     * @return array
     */
    protected function filterFillable(array $data)
    {
        return array_only($data, $this->fillable);
    }

    /**
     * Whether a offset exists.
     *
     * @param mixed $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->attributes);
    }

    /**
     * Offset to retrieve.
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    /**
     * Offset to set.
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Offset to unset.
     *
     * @param mixed $offset The offset to unset.
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }


    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->toArray();
    }
}
