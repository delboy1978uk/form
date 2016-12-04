<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 21:08
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMDocument;
use DOMElement;
use DOMText;

class DefaultRender implements FieldRendererInterface
{
    /** @var DOMDocument $dom  */
    private $dom;

    /** @var bool $displayErrors */
    private $displayErrors;

    /**
     * @param DOMDocument $dom
     * @return DOMElement
     */
    public function render(DOMDocument $dom, FieldInterface $field, $displayErrors = true)
    {
        $this->displayErrors = $displayErrors;
        $this->dom = $dom;
        $renderedField = $this->createFieldDom($field);
        return $renderedField;
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

        foreach ($field->getAttributes() as $key => $value) {
            $child->setAttribute($key, $value);
        }

        return $child;
    }
}