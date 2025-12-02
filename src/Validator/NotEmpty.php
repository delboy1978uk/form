<?php

declare(strict_types=1);

namespace Del\Form\Validator;

use Laminas\Validator\NotEmpty as ZfNotEmpty;

class NotEmpty implements ValidatorInterface
{
    private array $messages = [];

    public function isValid(mixed $value): bool
    {
        $validator = new  ZfNotEmpty();

        if ($validator->isValid($value)) {
            return true;
        }

        $this->messages = $validator->getMessages();

        return false;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
