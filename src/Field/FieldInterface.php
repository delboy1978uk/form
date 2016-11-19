<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 12:16
 */

namespace Del\Form\Field;


interface FieldInterface
{
    public function setValue($value);

    public function getValue();

    /**
     * @return string
     */
    public function getName();
}