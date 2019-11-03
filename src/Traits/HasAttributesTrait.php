<?php

namespace Del\Form\Traits;


trait HasAttributesTrait
{
    /** @var array $attributes */
    private $attributes = [];

    /**
     * @param string $key
     * @return null|mixed
     */
    public function getAttribute(string $key)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}