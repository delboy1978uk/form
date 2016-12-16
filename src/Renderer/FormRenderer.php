<?php
/**
 * User: delboy1978uk
 * Date: 29/11/2016
 * Time: 19:44
 */

namespace Del\Form\Renderer;

use Del\Form\Field\CheckBox;
use Del\Form\Field\Radio;
use DOMElement;
use DOMText;

class FormRenderer extends AbstractFormRenderer implements FormRendererInterface
{
    /**
     * @return DOMElement
     */
    public function renderFieldLabel()
    {
        $label = $this->createLabelElement();
        $text = new DOMText($this->field->getLabel());
        $label->appendChild($text);
        return $label;
    }

    /**
     * @return DOMElement
     */
    public function renderFieldBlock()
    {
        // Set form group div properties
        $formGroup = $this->block;
        $class = $formGroup->getAttribute('class').'form-group';
        $formGroup->setAttribute('class', $class);

        $formGroup->appendChild($this->label);

        $formGroup->appendChild($this->element);

        if (!is_null($this->errors)) {
            $formGroup->appendChild($this->errors);
        }

        // Field rendered! Pass it back!
        return $formGroup;
    }

}