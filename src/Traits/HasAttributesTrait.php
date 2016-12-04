<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 15:01
 */

namespace Del\Form\Traits;


trait HasAttributesTrait
{
    /** @var array $attributes */
    private $attributes = [];

    /**
     * @param $key
     * @return mixed|string
     */
    public function getAttribute($key)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}