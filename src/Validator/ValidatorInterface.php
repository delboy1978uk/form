<?php
/**
 * User: delboy1978uk
 * Date: 27/11/2016
 * Time: 13:43
 */

namespace Del\Form\Validator;

use Exception;
use Zend\Validator\ValidatorInterface as ZendValidatorInterface;

interface ValidatorInterface extends ZendValidatorInterface
{
    /**
     * @param  mixed $value
     * @return bool
     * @throws Exception If validation of $value is impossible
     */
    public function isValid($value);

    /**
     * @return array
     */
    public function getMessages();
}