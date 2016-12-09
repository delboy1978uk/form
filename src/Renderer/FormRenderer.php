<?php
/**
 * User: delboy1978uk
 * Date: 29/11/2016
 * Time: 19:44
 */

namespace Del\Form\Renderer;

use DOMElement;

class FormRenderer extends AbstractFormRenderer implements FormRendererInterface
{
    /**
     * @return DOMElement
     */
    public function renderFieldLabel()
    {
        $label = $this->dom->createElement('label');
        $label->setAttribute('for', $this->field->getId());
        $label->textContent = $this->field->getLabel();
        return $label;
    }

    /**
     * @return DOMElement
     */
    public function renderFieldBlock()
    {
        $formGroup = $this->block;
        $class = $formGroup->getAttribute('class');
        $formGroup->setAttribute('class', $class.'form-group');
        $formGroup->appendChild($this->label);
        $formGroup->appendChild($this->element);
        if (!is_null($this->errors)) {
            $formGroup->appendChild($this->errors);
        }
        return $formGroup;
    }

}