<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field;

class Submit extends FieldAbstract
{
    /**
     * @return string
     */
    public function getTag()
    {
        return 'input';
    }

    /**
     * @return string
     */
    public function getTagType()
    {
        return 'submit';
    }

    public function __construct($name, $value = null)
    {
        $value = is_null($value) ? $name : $value;
        parent::__construct($name, $value);
        $this->setClass('btn btn-primary');
    }
}