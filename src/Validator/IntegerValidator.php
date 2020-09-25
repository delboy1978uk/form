<?php

namespace Del\Form\Validator;

class IntegerValidator implements ValidatorInterface
{
    /**
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        return (bool) \filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return ['Value is not an integer.'];
    }

}