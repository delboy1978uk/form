<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 22:33
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use Del\Form\Field\Select;
use DOMElement;
use DOMText;
use InvalidArgumentException;

class SelectRender extends AbstractFieldRender implements FieldRendererInterface
{
    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $element)
    {
        if (!$field instanceof Select) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\Select');
        }
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
    private function processOption(FieldInterface $field, $value, $label)
    {
        $option = $this->createElement('option');
        $option->setAttribute('value', $value);
        $label = $this->createText($label);
        $option->appendChild($label);
        if ($field->getValue() == $option->getAttribute('value')) {
            $option->setAttribute('selected', 'selected');
        }
        return $option;
    }
}