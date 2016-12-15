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
    /** @var DOMElement $div */
    private $div;

    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $element)
    {
        // Since it's a containing div, remove the name
        $element->removeAttribute('name');
        $this->div = $element;

        // Make sure the FieldInterface is actually a Radio
        if (!$field instanceof Radio) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\Radio');
        }

        $inline = $field->isRenderInline();

        // Loop through each radio element (the options)
        foreach ($field->getOptions() as $value => $label) {
            $radio = $this->processOption($field, $value, $label, $inline);
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
    private function processOption(FieldInterface $field, $value, $labelText, $inline)
    {
        if ($inline === true) {
            return $this->renderRadioInline($field, $value, $labelText);
        }
        return $this->renderRadio($field, $value, $labelText);
    }

    private function renderRadio(FieldInterface $field, $value, $labelText)
    {
        $div = $this->dom->createElement('div');
        $div->setAttribute('class', 'radio');
        $radio = $this->renderRadioInline($field, $value, $labelText);
        $div->appendChild($radio);
        return $div;
    }

    private function renderRadioInline(FieldInterface $field, $value, $labelText)
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