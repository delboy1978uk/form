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
use InvalidArgumentException;

class SelectRender extends AbstractFieldRender implements FieldRendererInterface
{
    /**
     * @param FieldInterface $field
     * @param DOMElement $fieldBlock
     * @param DOMElement $labelBlock
     * @param DOMElement $element
     * @param DOMElement|null $errorBlock
     */
    public function renderFieldBlock(FieldInterface $field, DOMElement $fieldBlock, DOMElement $labelBlock, DOMElement $element, DOMElement $errorBlock = null)
    {
        if (!$field instanceof Select) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\Select');
        }
        foreach ($field->getOptions() as $value => $label) {
            $option = $this->dom->createElement('option');
            $option->setAttribute('value', $value);
            $option->textContent = $label;
            $element->appendChild($option);
        }
        $fieldBlock->appendChild($labelBlock);
        $fieldBlock->appendChild($element);
        if ($errorBlock) {
            $fieldBlock->appendChild($errorBlock);
        }
        return $fieldBlock;
    }
}