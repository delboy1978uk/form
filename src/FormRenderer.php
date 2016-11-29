<?php
/**
 * User: delboy1978uk
 * Date: 29/11/2016
 * Time: 19:44
 */

namespace Del\Form;

use Del\Form\Field\FieldInterface;
use DOMDocument;
use DOMElement;

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

    public function render(AbstractForm $form, $displayErrors = true)
    {
        $this->displayErrors = $displayErrors;
        $method = $form->getMethod() ?: AbstractForm::METHOD_POST;
        $id = $form->getId() ?: $this->form->getAttribute('name');
        $action = $form->getAction() ?: $this->form->getAttribute('action');
        $encType = $form->getEncType() ?: $this->form->getAttribute('enc-type');
        $class = $form->getClass();

        $this->form->setAttribute('id', $id);
        $this->form->setAttribute('method', $method);
        $this->form->setAttribute('class', $class);
        $this->form->setAttribute('action', $action);
        $this->form->setAttribute('enctype', $encType);

        $fields = $form->getFields();

        $fields->rewind();
        while ($fields->valid()) {
            $current = $fields->current();
            $child = $this->createFieldDOM($current);
            $this->form->appendChild($child);
            $fields->next();
        }
        $fields->rewind();

        $this->dom->appendChild($this->form);

        return $this->dom->saveHTML();
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

        if (!$field->isValid() && $this->displayErrors == true) {
            $formGroup = $this->createHelpBlock($formGroup, $field->getMessages());
        }

        return $formGroup;
    }

    private function createHelpBlock(DOMElement $formGroup, array $messages)
    {
        $formGroup->setAttribute('class', 'form-group has-error');
        $helpBlock = $this->dom->createElement('span');
        $helpBlock->setAttribute('class', 'help-block');
        $errorMessages = '';
        foreach ($messages as $message) {
            if(is_array($message)) {
                foreach ($message as $m) {
                    $errorMessages .= $m."\n";
                }
            } else {
                $errorMessages .= $message."\n";
            }
        }
        $helpBlock->textContent = $errorMessages;
        $formGroup->appendChild($helpBlock);
        return $formGroup;
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