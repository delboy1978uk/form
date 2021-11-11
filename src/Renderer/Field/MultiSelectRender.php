<?php declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use Del\Form\Field\MultiSelect;
use Del\Form\Field\Select;
use DOMElement;
use InvalidArgumentException;

class MultiSelectRender extends AbstractFieldRender
{
    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $element): DOMElement
    {
        if (!$field instanceof MultiSelect) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\MultiSelect');
        }

        $element->setAttribute('name', $field->getName() . '[]');

        foreach ($field->getOptions() as $value => $label) {
            $option = $this->processOption($field, $value, $label);
            $element->appendChild($option);
        }

        return $element;
    }

    /**
     * @param FieldInterface $field
     * @param string $value
     * @param string $label
     * @return DOMElement
     */
    private function processOption(FieldInterface $field, $value, $label): DOMElement
    {
        $option = $this->createElement('option');
        $option->setAttribute('value', (string) $value);
        $label = $this->createText($label);
        $option->appendChild($label);

        if ($field->getValue() !== null && in_array($option->getAttribute('value'), $field->getValue())) {
            $option->setAttribute('selected', 'selected');
        }

        return $option;
    }
}
