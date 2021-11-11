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

    private $counter = 0;

    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMNode
     */
    public function renderBlock(FieldInterface $field, DOMElement $element)
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


    /**
     * @param FieldInterface $field
     * @param $value
     * @param $labelText
     * @return DOMElement
     */
    private function processOption(FieldInterface $field, $value, $labelText, $inline)
    {
        return $this->renderCheckbox($field, $value, $labelText, $inline);
    }

    /**
     * @param CheckBox $field
     * @param $value
     * @param $labelText
     * @return DOMElement
     */
    private function renderCheckbox(CheckBox $field, $value, $labelText, $inline)
    {
        $div = $this->getDom()->createElement('div');
        $class = $inline ? 'form-check-inline' : 'form-check';
        $div->setAttribute('class', $class);
        $checkbox = $this->renderCheckboxInline($field, $value);
        $div->appendChild($checkbox);
        $div->appendChild($this->getLabel($field, $labelText));

        return $div;
    }

    /**
     * @param FieldInterface $field
     * @param string $labelText
     * @return DOMElement
     */
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

    /**
     * @param FieldInterface $field
     * @param $value
     * @param $labelText
     * @return DOMElement
     */
    private function renderCheckboxInline(FieldInterface $field, $value)
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
