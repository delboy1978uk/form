<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 12:16
 */

namespace Del\Form\Field;

interface FieldInterface
{
    /**
     * @param mixed $value
     * @return FieldInterface
     */
    public function setValue($value);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getTag();

    /**
     * @return mixed
     */
    public function getClass();

    /**
     * @return mixed
     */
    public function getTagType();
}