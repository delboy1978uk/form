<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 12:13
 */

namespace Del\Form;

use Del\Form\Collection\FieldCollection;
use Del\Form\Field\FieldInterface;
use DOMDocument;
use DOMElement;

abstract class AbstractForm implements FormInterface
{
    const ENC_TYPE_MULTIPART_FORM_DATA = 'multipart/form-data';
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    /** @var FieldCollection $fieldCollection */
    private $fieldCollection;

    /** @var DOMDocument $dom */
    private $dom;

    /** @var DomElement $form */
    private $form;

    /**
     * @var array
     */
    private $errorMessages;

    /**
     * AbstractForm constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->fieldCollection = new FieldCollection();
        $this->dom = new DOMDocument();
        $form = $this->dom->createElement('form');
        $form->setAttribute('name', $name);
        $this->form = $form;
        $this->init();
    }

    abstract public function init();

    /**
     * @return bool
     */
    public function isValid()
    {
        $this->errorMessages = [];
        $this->fieldCollection->rewind();
        while ($this->fieldCollection->valid()) {
            $this->checkForErrors($this->fieldCollection->current());
            $this->fieldCollection->next();
        }
        $this->fieldCollection->rewind();
        return count($this->errorMessages) == 0;
    }

    /**
     * @param FieldInterface $field
     */
    private function checkForErrors(FieldInterface $field)
    {
        if ($field->isValid()) {
            $this->errorMessages[$field->getName()] = $field->getMessages();
        }
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = [];
        $this->fieldCollection->rewind();
        while ($this->fieldCollection->valid()) {
            $field = $this->fieldCollection->current();
            $values[$field->getName()] = $field->getValue();
            $this->fieldCollection->next();
        }
        $this->fieldCollection->rewind();
        return $values;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function populate(array $data)
    {
        $this->fieldCollection->rewind();
        while ($this->fieldCollection->valid()) {
            $field = $this->fieldCollection->current();
            $name = $field->getName();
            if (isset($data[$name])) {
                $field->setValue($data[$name]);
            }
            $this->fieldCollection->next();
        }
        $this->fieldCollection->rewind();
        return $this;
    }

    /**
     * @param string $name
     * @return FieldInterface|null
     */
    public function getField($name)
    {
        return $this->fieldCollection->findByName($name);
    }

    /**
     * @return FieldCollection
     */
    public function getFields()
    {
        return $this->fieldCollection;
    }

    /**
     * @param FieldInterface $field
     * @return $this
     */
    public function addField(FieldInterface $field)
    {
        $this->fieldCollection->append($field);
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $method = $this->form->getAttribute('method') ?: self::METHOD_POST;
        $id = $this->form->getAttribute('id') ?: $this->form->getAttribute('name');
        $action = $this->form->getAttribute('action') ?: $this->form->getAttribute('action');

        $this->form->setAttribute('id', $id);
        $this->form->setAttribute('method', $method);
        $this->form->setAttribute('action', $action);

        $this->fieldCollection->rewind();
        while ($this->fieldCollection->valid()) {
            /** @var FieldInterface $current */
            $current = $this->fieldCollection->current();
            $child = $this->createChildElement($current);
            $this->form->appendChild($child);
            $this->fieldCollection->next();
        }
        $this->fieldCollection->rewind();

        $this->dom->appendChild($this->form);

        return $this->dom->saveHTML();
    }

    /**
     * @param FieldInterface $field
     * @return DOMElement
     */
    private function createChildElement(FieldInterface $field)
    {
        $child = $this->dom->createElement($field->getTag());

        $child->setAttribute('type', $field->getTagType());
        $child->setAttribute('name', $field->getName());
        $child->setAttribute('id', $field->getId());
        $child->setAttribute('value', $field->getValue());
        $child->setAttribute('class', $field->getClass());

        return $child;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setAction($url)
    {
        $this->form->setAttribute('action', $url);
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->form->getAttribute('action');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->form->getAttribute('id');
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->form->setAttribute('id', $id);
        return $this;
    }

    /**
     * @param $encType
     * @return $this
     */
    public function setEncType($encType)
    {
        $this->form->setAttribute('enctype', $encType);
        return $this;
    }

    /**
     * @return string
     */
    public function getEncType()
    {
        return $this->form->getAttribute('enctype');
    }

    /**
     * @param string $method
     * @return FormInterface
     */
    public function setMethod($method)
    {
        $this->form->setAttribute('method', $method);
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->form->getAttribute('method');
    }

    /**
     * @param $class
     * @return FormInterface
     */
    public function setClass($class)
    {
        $this->form->setAttribute('class', $class);
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->form->getAttribute('class');
    }
}