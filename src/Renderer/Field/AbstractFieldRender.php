<?php

declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\ArrayValueInterface;
use Del\Form\Field\FieldInterface;
use Del\Form\Traits\HasDomTrait;
use DOMDocument;
use DOMElement;
use DOMNode;

abstract class AbstractFieldRender implements FieldRendererInterface
{
    use HasDomTrait;

    public function render(DOMDocument $dom, FieldInterface $field): DOMNode
    {
        $this->setDom($dom);
        $element = $this->createElementFromField($field);

        return $this->renderBlock($field, $element);
    }

    public function createElementFromField(FieldInterface $field): DOMNode
    {
        $element = $this->createElement($field->getTag());

        foreach ($field->getAttributes() as $key => $value) {
            $element = $this->setAttribute($field, $element, $key, $value);
        }

        return $element;
    }

    private function setAttribute(FieldInterface $field, DOMElement $element, $key, $value): DOMNode
    {
        if ($field instanceof ArrayValueInterface && $key === 'value') {
            return $element;
        }

        $element->setAttribute($key, (string) $value);

        return $element;
    }

    abstract public function renderBlock(FieldInterface $field, DOMElement $element): DOMNode;
}
