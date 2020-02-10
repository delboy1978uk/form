<?php

namespace Del\Form\Validator\Adapter;

use Del\Form\Validator\ValidatorInterface;
use Exception;
use Laminas\Validator\ValidatorInterface as LaminasValidatorInterface;

class ValidatorAdapterZf implements ValidatorInterface
{
    private $validator;

    /**
     * ValidatorAdapterZf constructor.
     */
    public function __construct(LaminasValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param  mixed $value
     * @return bool
     * @throws Exception If validation of $value is impossible
     */
    public function isValid($value)
    {
        return $this->validator->isValid($value);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->validator->getMessages();
    }


}