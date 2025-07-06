<?php

declare(strict_types=1);

namespace Del\Form\Validator;

use function filter_var;

class FloatValidator implements ValidatorInterface
{
    public function isValid($value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    public function getMessages(): array
    {
        return ['Value is not a float.'];
    }
}
