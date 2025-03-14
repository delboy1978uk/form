<?php

declare(strict_types=1);

namespace Del\Form\Exception;

use Bone\Exception;

class FormValidationException extends Exception
{
    public function __construct(array $errors)
    {
        $message = \implode(', ', $errors);
        parent::__construct($message, 400);
    }
}
