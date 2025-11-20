<?php

declare(strict_types=1);

namespace Del\Form\Field;

interface TransformerInterface
{
    public function input(mixed $data): string;
    public function output(string $value);
}
