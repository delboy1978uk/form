<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:41
 */

namespace Del\Form\Field;

abstract class FieldAbstract implements FieldInterface
{
    /** @var string $name */
    private $name;

    /** @var string $id */
    private $id;

    /** @var string $class  */
    private $class;

    private $value;

    /**
     * @return string
     */
    abstract public function getTag();

    /**
     * @return mixed
     */
    abstract public function getTagType();

    public function __construct($name, $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FieldAbstract
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return FieldAbstract
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class ?: 'form-control';
    }

    /**
     * @param string $class
     * @return FieldAbstract
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return FieldAbstract
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}