<?php

declare(strict_types=1);

namespace Del\Form\Validator;

class IntegerValidator implements ValidatorInterface
{
    public function isValid(mixed $value): bool
    {
        return (bool) \filter_var($value, FILTER_VALIDATE_INT);
    }

    public function getMessages(): array
    {
        return ['Value is not an integer.'];
    }
}
