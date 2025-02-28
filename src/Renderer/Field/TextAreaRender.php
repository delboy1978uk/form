<?php declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMElement;

class TextAreaRender extends AbstractFieldRender
{
    public function renderBlock(FieldInterface $field, DOMElement $element): DOMElement
    {
        $element->removeAttribute('type');
        $element->removeAttribute('value');
        $text = $this->createText($field->getValue());
        $element->appendChild($text);
        return $element;
    }

}
