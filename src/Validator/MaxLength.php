<?php

declare(strict_types=1);

namespace Del\Form\Validator;

use function strlen;

class MaxLength implements ValidatorInterface
{
    public function __construct(
        private int $maxLength
    ) {}

    public function isValid(mixed $value): bool
    {
        return strlen($value) <= $this->maxLength;
    }

    public function getMessages(): array
    {
        return ['Exceeded maximum length of ' . $this->maxLength];
    }
}
