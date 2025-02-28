<?php

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use Del\Form\Field\Radio;
use DOMDocumentFragment;
use DOMElement;
use DOMNode;
use InvalidArgumentException;
use LogicException;

class RadioRender extends AbstractFieldRender
{
    private DOMDocumentFragment $fragment;
    private int $counter = 0;

    public function renderBlock(FieldInterface $field, DOMElement $element): DOMNode
    {
        // We don't really want a containing div, so we'll ignore $element
        // and instead create a DOMDocumentFragment
        $this->fragment = $this->getDom()->createDocumentFragment();

        // Make sure the FieldInterface is actually a Radio
        if (!$field instanceof Radio) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\Radio');
        }

        $inline = $field->isRenderInline();
        $options = $field->getOptions();

        if (empty($options)) {
            throw new LogicException('You must set at least one option.');
        }

        // Loop through each radio element (the options)
        foreach ($options as $value => $label) {
            $radio = $this->renderRadio($field, $value, $label, $inline);
            $this->fragment->appendChild($radio);
        }

        return $this->fragment;
    }

    private function renderRadio(FieldInterface $field, $value, $labelText, $inline): DOMElement
    {
        $div = $this->createElement('div');
        $class = $inline ? 'form-check-inline' : 'form-check';
        $div->setAttribute('class', $class);
        $radio = $this->renderRadioInline($field, $value, $labelText);
        $label = $this->getLabel($field, $labelText);
        $div->appendChild($radio);
        $div->appendChild($label);

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

    private function renderRadioInline(FieldInterface $field, $value, $labelText): DOMElement
    {
        $radio = $this->createElement('input');
        $radio->setAttribute('class', 'form-check-input');
        $radio->setAttribute('type', 'radio');
        $radio->setAttribute('name', $field->getName());
        $radio->setAttribute('value', $value);
        /** @todo label? do we eed this? */
        $text = $this->createText($labelText);

        if ($field->getValue() == $radio->getAttribute('value')) {
            $radio->setAttribute('checked', 'checked');
        }

        return $radio;
    }
}
