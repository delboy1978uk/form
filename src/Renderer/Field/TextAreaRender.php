<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 21:08
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMElement;

class TextAreaRender extends AbstractFieldRender implements FieldRendererInterface
{
    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $element)
    {
        $element->removeAttribute('type');
        $element->removeAttribute('value');
        $text = $this->createText($field->getValue());
        $element->appendChild($text);
        return $element;
    }

}