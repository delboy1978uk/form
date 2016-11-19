<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 12:13
 */

namespace Del\Form;

use Del\Form\Collection\FieldCollection;
use Del\Form\Field\FieldInterface;

abstract class AbstractForm implements FormInterface
{
    /** @var FieldCollection $fieldCollection */
    private $fieldCollection;

    /** @var string $name */
    private $name;

    public function __construct($name)
    {
        $this->fieldCollection = new FieldCollection();
        $this->init();
    }

    abstract public function init();

    /**
     * @return bool
     */
    public function isValid()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return [];
    }

    /**
     * @param array $data
     * @return $this
     */
    public function populate(array $data)
    {
        /** @var FieldInterface $field */
        foreach ($this->fieldCollection as $field) {
            $name = $field->getName();
            if (isset($data[$name])) {
                $field->setValue($data[$name]);
            }
        }
        return $this;
    }

    public function getField($name)
    {
        return $this->fieldCollection->findByName($name);
    }

    public function getFields()
    {
        return $this->fieldCollection;
    }

    public function addField(FieldInterface $field)
    {
        $this->fieldCollection->append($field);
        return $this;
    }


    public function render()
    {
        return '<form name="'.$this->name.'">Render Fields Here</form>';
    }

}