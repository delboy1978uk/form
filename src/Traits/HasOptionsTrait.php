<?php

declare(strict_types=1);

namespace Del\Form\Traits;

trait HasOptionsTrait
{
    private array $options = [];

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions($options): void
    {
        $this->options = $options;
    }

    public function getOption($key): mixed
    {
        return $this->options[$key];
    }

    public function setOption($key, $value): void
    {
        $this->options[$key] = $value;
    }
}
