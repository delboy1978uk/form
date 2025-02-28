<?php

declare(strict_types=1);

namespace Del\Form\Validator\Adapter;

use Del\Form\Validator\ValidatorInterface;
use Exception;
use Laminas\Validator\ValidatorInterface as LaminasValidatorInterface;

class ValidatorAdapterZf implements ValidatorInterface
{
    public function __construct(
        private LaminasValidatorInterface $validator
    ) {}

    /**
     * @todo exception types
     * @throws Exception If validation of $value is impossible
     */
    public function isValid(mixed $value): bool
    {
        return $this->validator->isValid($value);
    }

    public function getMessages(): array
    {
        return $this->validator->getMessages();
    }


}
