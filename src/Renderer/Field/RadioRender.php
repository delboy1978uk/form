<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 22:33
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMElement;
use DOMText;

class RadioRender extends AbstractFieldRender implements FieldRendererInterface
{
    /**
     * @param FieldInterface $field
     * @param DOMElement $fieldBlock
     * @param DOMElement $labelBlock
     * @param DOMElement $element
     * @param DOMElement|null $errorBlock
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $element)
    {
        $label = $this->dom->createElement('label');
        $label->setAttribute('for', $field->getId());
        $label->appendChild($element);
        $text = new DOMText($field->getLabel());
        $label->appendChild($text);
        return $label;
    }

}