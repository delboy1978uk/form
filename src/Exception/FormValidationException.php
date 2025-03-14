<?php

declare(strict_types=1);

namespace Del\Form\Exception;

use Bone\Exception;

class FormValidationException extends Exception
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        $message = \implode(', ', $errors);
        parent::__construct($message, 400);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
