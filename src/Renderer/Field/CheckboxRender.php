<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 22:33
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use Del\Form\Field\CheckBox;
use DOMDocumentFragment;
use DOMElement;
use DOMNode;
use DOMText;
use InvalidArgumentException;

class CheckboxRender extends AbstractFieldRender implements FieldRendererInterface
{
    /** @var DOMDocumentFragment $div */
    private $fragment;

    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMNode
     */
    public function renderBlock(FieldInterface $field, DOMElement $element)
    {
        // We don't really want a containing div, so we'll ignore $element
        // and instead create a DOMDocumentFragment
        unset($element);
        $this->fragment = $this->dom->createDocumentFragment();

        // Make sure the FieldInterface is actually a Radio
        if (!$field instanceof CheckBox) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\Checkbox');
        }

        $inline = $field->isRenderInline();

        // Loop through each radio element (the options)
        foreach ($field->getOptions() as $value => $label) {
            $radio = $this->processOption($field, $value, $label, $inline);
            $this->fragment->appendChild($radio);
        }

        return $this->fragment;
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
            return $this->renderCheckboxInline($field, $value, $labelText);
        }
        return $this->renderRadio($field, $value, $labelText);
    }

    /**
     * @param FieldInterface $field
     * @param $value
     * @param $labelText
     * @return DOMElement
     */
    private function renderRadio(FieldInterface $field, $value, $labelText)
    {
        $div = $this->dom->createElement('div');
        $div->setAttribute('class', 'checkbox');
        $radio = $this->renderCheckboxInline($field, $value, $labelText);
        $radio->removeAttribute('class');
        $div->appendChild($radio);
        return $div;
    }

    /**
     * @param FieldInterface $field
     * @param $value
     * @param $labelText
     * @return DOMElement
     */
    private function renderCheckboxInline(FieldInterface $field, $value, $labelText)
    {
        $label = $this->dom->createElement('label');
        $label->setAttribute('for', $field->getId());
        $label->setAttribute('class', 'checkbox-inline');

        $radio = $this->dom->createElement('input');
        $radio->setAttribute('type', 'checkbox');
        $radio->setAttribute('name', $field->getName());
        $radio->setAttribute('value', $value);
        $text = new DOMText($labelText);

        if($field->getValue() == $radio->getAttribute('value')) {
            $radio->setAttribute('checked', 'checked');
        }

        $label->appendChild($radio);
        $label->appendChild($text);

        return $label;
    }
}