<?php

namespace Del\Form\Traits;


trait HasAttributesTrait
{
    private array $attributes = [];

    public function getAttribute(string $key): mixed
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
