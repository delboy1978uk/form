<?php

declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use Del\Form\Field\CheckBox;
use DOMDocumentFragment;
use DOMElement;
use DOMNode;
use InvalidArgumentException;
use LogicException;

class CheckboxRender extends AbstractFieldRender
{
    private DOMDocumentFragment $fragment;
    private bool $isMultiCheckbox = false;
    private int $counter = 0;

    public function renderBlock(FieldInterface $field, DOMElement $element): DOMNode
    {
        // We don't really want a containing div, so we'll ignore $element
        // and instead create a DOMDocumentFragment
        $this->fragment = $this->getDom()->createDocumentFragment();

        // Make sure the FieldInterface is actually a Radio
        if (!$field instanceof CheckBox) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\Checkbox');
        }

        $inline = $field->isRenderInline();

        $options = $field->getOptions();
        if (empty($options)) {
            throw new LogicException('You must set at least one option.');
        }

        // Loop through each checkbox element (the options)
        $this->isMultiCheckbox = count($options) > 1;
        foreach ($options as $value => $label) {
            $radio = $this->processOption($field, $value, $label, $inline);
            $this->fragment->appendChild($radio);
        }

        return $this->fragment;
    }

    private function processOption(CheckBox $field, $value, $labelText, $inline): DOMElement
    {
        return $this->renderCheckbox($field, $value, $labelText, $inline);
    }

    private function renderCheckbox(CheckBox $field, $value, $labelText, $inline): DOMElement
    {
        $div = $this->getDom()->createElement('div');
        $class = $inline ? 'form-check-inline' : 'form-check';
        $div->setAttribute('class', $class);
        $checkbox = $this->renderCheckboxInline($field, $value);
        $div->appendChild($checkbox);
        $div->appendChild($this->getLabel($field, $labelText));

        return $div;
    }

    private function getLabel(FieldInterface $field, string $labelText): DOMElement
    {
        $this->counter ++;
        $label = $this->getDom()->createElement('label');
        $label->setAttribute('for', $field->getId() . $this->counter);
        $label->setAttribute('class', 'form-check-label');
        $text = $this->createText($labelText);
        $label->appendChild($text);

        return $label;
    }

    private function renderCheckboxInline(FieldInterface $field, $value): DOMElement
    {
        $checkbox = $this->getDom()->createElement('input');
        $checkbox->setAttribute('class', 'form-check-input');
        $checkbox->setAttribute('type', 'checkbox');
        $fieldName = $this->isMultiCheckbox ? $field->getName() . '[]' : $field->getName();
        $checkbox->setAttribute('name', $fieldName);
        $checkbox->setAttribute('value', $value);
        $fieldValue = $field->getValue();

        if ($fieldValue === true || $fieldValue == $value || (is_array($fieldValue) && in_array($value, $fieldValue, true))) {
            $checkbox->setAttribute('checked', 'checked');
        }

        return $checkbox;
    }
}
