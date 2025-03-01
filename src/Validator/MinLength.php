<?php

declare(strict_types=1);

namespace Del\Form\Validator;

use function strlen;

class MinLength implements ValidatorInterface
{
    public function __construct(
        private int $minLength
    ) {}

    public function isValid(mixed $value): bool
    {
        return strlen($value) >= $this->minLength;
    }

    public function getMessages(): array
    {
        return ['Minimum length must be ' . $this->minLength];
    }
}
