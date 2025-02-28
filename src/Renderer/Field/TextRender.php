<?php declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMElement;

class TextRender extends AbstractFieldRender
{
    public function renderBlock(FieldInterface $field, DOMElement $element): DOMElement
    {
        return $element;
    }
}
