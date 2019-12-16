<?php declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\ArrayValueInterface;
use Del\Form\Field\FieldInterface;
use Del\Form\Traits\HasDomTrait;
use DOMDocument;
use DOMElement;

abstract class AbstractFieldRender implements FieldRendererInterface
{
    use HasDomTrait;

    /**
     * @param DOMDocument $dom
     * @return DOMElement
     */
    public function render(DOMDocument $dom, FieldInterface $field)
    {
        $this->setDom($dom);
        $element = $this->createElementFromField($field);

        return $this->renderBlock($field, $element);
    }

    /**
     * @param FieldInterface $field
     * @return DOMElement
     */
    public function createElementFromField(FieldInterface $field): DOMElement
    {
        $element = $this->createElement($field->getTag());

        foreach ($field->getAttributes() as $key => $value) {
            $element = $this->setAttribute($field, $element, $key, $value);
        }

        return $element;
    }

    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @param $key
     * @param $value
     * @return DOMElement
     */
    private function setAttribute(FieldInterface $field, DOMElement $element, $key, $value): DOMElement
    {
        if ($field instanceof ArrayValueInterface && $key == 'value') {
            return $element;
        }

        $element->setAttribute($key, (string) $value);

        return $element;
    }

    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMElement
     */
    abstract public function renderBlock(FieldInterface $field, DOMElement $element);
}