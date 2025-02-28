<?php

declare(strict_types=1);

namespace Del\Form\Renderer;

use DOMElement;
use DOMText;

class FormRenderer extends AbstractFormRenderer
{
    public function renderFieldLabel(): DOMElement
    {
        $label = $this->createLabelElement();
        $text = new DOMText($this->field->getLabel() ?? '');
        $label->appendChild($text);
        return $label;
    }

    public function renderFieldBlock(): DomElement
    {
        // Set form group div properties
        $formGroup = $this->block;
        $class = $formGroup->getAttribute('class').'form-group';
        $formGroup->setAttribute('class', $class);
        $formGroup->setAttribute('id', $this->field->getName().'-form-group');

        $formGroup->appendChild($this->label);

        $formGroup->appendChild($this->element);

        if (!is_null($this->errors)) {
            $formGroup->appendChild($this->errors);
        }

        return $formGroup;
    }
}
