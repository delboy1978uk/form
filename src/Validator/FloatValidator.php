<?php

namespace Del\Form\Validator;

class FloatValidator implements ValidatorInterface
{
    /**
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        return (bool) \filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return ['Value is not a float.'];
    }

}