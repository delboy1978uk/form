<?php
/**
 * User: delboy1978uk
 * Date: 29/11/2016
 * Time: 19:44
 */

namespace Del\Form\Renderer;

use Del\Form\Collection\FieldCollection;
use Del\Form\Field\FieldInterface;
use Del\Form\AbstractForm;
use Del\Form\FormInterface;
use DOMDocument;
use DOMElement;
use DOMText;

class FormRenderer
{
    /** @var DOMDocument $dom */
    private $dom;

    /** @var DomElement $form */
    private $form;

    /** @var bool $displayErrors */
    private $displayErrors;

    public function __construct($name)
    {
        $this->dom = new DOMDocument();
        $form = $this->dom->createElement('form');
        $form->setAttribute('name', $name);
        $this->form = $form;
    }

    /**
     * @param FormInterface $form
     * @param bool $displayErrors
     * @return string
     */
    public function render(FormInterface $form, $displayErrors = true)
    {
        $this->displayErrors = $displayErrors;
        $this->setFormAttributes($form);

        $fields = $form->getFields();
        $this->processFields($fields);

        $this->dom->appendChild($this->form);
        return $this->dom->saveHTML();
    }

    /**
     * @param FormInterface $form
     */
    private function setFormAttributes(FormInterface $form)
    {
        $method = $this->getMethod($form);
        $id = $this->getId($form);
        $action = $this->getAction($form);
        $encType = $this->getEncType($form);
        $class = $form->getClass();

        $this->form->setAttribute('id', $id);
        $this->form->setAttribute('method', $method);
        $this->form->setAttribute('class', $class);
        $this->form->setAttribute('action', $action);
        $this->form->setAttribute('enctype', $encType);
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    private function getMethod(FormInterface $form)
    {
        return $form->getMethod() ?: AbstractForm::METHOD_POST;
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    private function getId(FormInterface $form)
    {
        return $form->getId() ?: $this->form->getAttribute('name');
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    private function getAction(FormInterface $form)
    {
        return $form->getAction() ?: $this->form->getAttribute('action');
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    private function getEncType(FormInterface $form)
    {
        return $form->getEncType() ?: $this->form->getAttribute('enc-type');
    }

    private function processFields(FieldCollection $fields)
    {
        $fields->rewind();
        while ($fields->valid()) {
            $current = $fields->current();
            $child = $this->createFieldDOM($current);
            $this->form->appendChild($child);
            $fields->next();
        }
        $fields->rewind();
    }

    /**
     * @param FieldInterface $field
     * @return DOMElement
     */
    private function createFieldDOM(FieldInterface $field)
    {
        $formGroup = $this->dom->createElement('div');
        $formGroup->setAttribute('class', 'form-group');

        $label = $this->dom->createElement('label');
        $label->setAttribute('for', $field->getId());
        $label->textContent = $field->getLabel();

        $formField = $this->createChildElement($field);

        $formGroup->appendChild($label);
        $formGroup->appendChild($formField);

        if (!$field->isValid() && $this->displayErrors === true) {
            $formGroup = $this->createHelpBlock($formGroup, $field);
        }

        return $formGroup;
    }

    /**
     * @param DOMElement $formGroup
     * @param FieldInterface $field
     * @return DOMElement]
     */
    private function createHelpBlock(DOMElement $formGroup, FieldInterface $field)
    {
        $formGroup->setAttribute('class', 'form-group has-error');
        $helpBlock = $this->dom->createElement('span');
        $helpBlock->setAttribute('class', 'help-block');

        if ($field->hasCustomErrorMessage()) {
            $helpBlock = $this->addCustomErrorMessage($helpBlock, $field);
        } else {
            $helpBlock = $this->addErrorMessages($helpBlock, $field);
        }
        $formGroup->appendChild($helpBlock);
        return $formGroup;
    }

    /**
     * @param DOMElement $helpBlock
     * @param FieldInterface $field
     * @return DOMElement
     */
    private function addCustomErrorMessage(DOMElement $helpBlock, FieldInterface $field)
    {
        $message = $field->getCustomErrorMessage();
        $text = new DOMText($message);
        $helpBlock->appendChild($text);
        return $helpBlock;
    }

    /**
     * @param DOMElement $helpBlock
     * @param FieldInterface $field
     * @return DOMElement]
     */
    private function addErrorMessages(DOMElement $helpBlock, FieldInterface $field)
    {
        $messages = $field->getMessages();

        foreach ($messages as $message) {
            $helpBlock = $this->appendMessage($helpBlock, $message);
        }
        return $helpBlock;
    }

    /**
     * @param DOMElement $helpBlock
     * @param $message
     * @return DOMElement
     */
    private function appendMessage(DOMElement $helpBlock, $message)
    {
        $text = new DOMText($message);
        $br = $this->dom->createElement('br');
        $helpBlock->appendChild($text);
        $helpBlock->appendChild($br);
        return $helpBlock;
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
}