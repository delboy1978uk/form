<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 22:33
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use Del\Form\Field\Radio;
use DOMElement;
use DOMText;
use InvalidArgumentException;

class RadioRender extends AbstractFieldRender implements FieldRendererInterface
{
    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $element)
    {
        $element->removeAttribute('name');
        if (!$field instanceof Radio) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\Radio');
        }
        foreach ($field->getOptions() as $value => $label) {
            $radio = $this->processOption($field, $value, $label);
            $element->appendChild($radio);
        }
        return $element;
    }


    /**
     * @param FieldInterface $field
     * @param $value
     * @param $labelText
     * @return DOMElement
     */
    private function processOption(FieldInterface $field, $value, $labelText)
    {
        $label = $this->dom->createElement('label');
        $label->setAttribute('for', $field->getId());

        $radio = $this->dom->createElement('input');
        $radio->setAttribute('type', 'radio');
        $radio->setAttribute('name', $field->getName());
        $radio->setAttribute('value', $value);
        $radio->setAttribute('value', $value);
        $text = new DOMText($labelText);

        $label->appendChild($radio);
        $label->appendChild($text);

        if($field->getValue() == $radio->getAttribute('value')) {
            $radio->setAttribute('checked', 'checked');
        }
        return $label;
    }
}