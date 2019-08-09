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
use LogicException;

class CheckboxRender extends AbstractFieldRender implements FieldRendererInterface
{
    /** @var DOMDocumentFragment $div */
    private $fragment;

    /** @var bool $isMultiCheckbox */
    private $isMultiCheckbox = false;

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
        return $this->renderCheckbox($field, $value, $labelText);
    }

    /**
     * @param FieldInterface $field
     * @param $value
     * @param $labelText
     * @return DOMElement
     */
    private function renderCheckbox(FieldInterface $field, $value, $labelText)
    {
        $div = $this->getDom()->createElement('div');
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
        $label = $this->getDom()->createElement('label');
        $label->setAttribute('for', $field->getId());
        $label->setAttribute('class', 'checkbox-inline');

        $radio = $this->getDom()->createElement('input');
        $radio->setAttribute('type', 'checkbox');
        $fieldName = $this->isMultiCheckbox ? $field->getName() . '[]' : $field->getName();
        $radio->setAttribute('name', $fieldName);
        $radio->setAttribute('value', $value);
        $text = $this->createText($labelText);
        $fieldValue = $field->getValue();

        if ($fieldValue === true || (is_array($fieldValue) && in_array($value, $fieldValue, true))) {
            $radio->setAttribute('checked', 'checked');
        }

        $label->appendChild($radio);
        $label->appendChild($text);

        return $label;
    }
}
