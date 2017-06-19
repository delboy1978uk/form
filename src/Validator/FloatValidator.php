<?php
/**
 * User: delboy1978uk
 * Date: 26/12/2016
 * Time: 14:59
 */

namespace Del\Form\Validator;

use Exception;

class FloatValidator implements ValidatorInterface
{
    /**
     * @param  mixed $value
     * @return bool
     * @throws Exception If validation of $value is impossible
     */
    public function isValid($value)
    {
        if (!is_numeric($value)) {
            return false;
        }
        return is_float((float) $value);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return ['Value is not a float.'];
    }

}