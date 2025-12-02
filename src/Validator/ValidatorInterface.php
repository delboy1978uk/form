<?php

declare(strict_types=1);

namespace Del\Form\Validator;

use Laminas\Validator\ValidatorInterface as LaminasValidatorInterface;

interface ValidatorInterface extends LaminasValidatorInterface
{
    public function isValid(mixed $value): bool;
    public function getMessages();
}
