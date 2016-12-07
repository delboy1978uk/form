<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 23:13
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use Del\Form\Renderer\Field\Error\DefaultErrorRender;
use Del\Form\Renderer\Field\Error\ErrorRendererInterface;
use DOMDocument;
use DOMElement;

abstract class AbstractFieldRender implements FieldRendererInterface
{
    /** @var DOMDocument $dom  */
    protected $dom;

    /** @var bool $displayErrors */
    private $displayErrors;

    /** @var ErrorRendererInterface $errorRenderer */
    private $errorRenderer;

    /**
     * @param DOMDocument $dom
     * @return DOMElement
     */
    public function render(DOMDocument $dom, FieldInterface $field, $displayErrors = true)
    {
        $this->displayErrors = $displayErrors;
        $this->dom = $dom;
        $this->errorRenderer = new DefaultErrorRender($this->dom);
        $renderedField = $this->createFieldDom($field);
        return $renderedField;
    }


    /**
     * @param FieldInterface $field
     * @return DOMElement
     */
    private function createFieldDOM(FieldInterface $field)
    {
        $fieldBlock = $this->createFieldBlock();
        $labelBlock = $this->createLabelBlock($field);
        $element = $this->createElement($field);
        $errorBlock = $this->createErrorBlock($fieldBlock, $field);
        return $this->renderBlock($field, $fieldBlock, $labelBlock, $element, $errorBlock);
    }

    /**
     * @param FieldInterface $field
     * @return DOMElement|null
     */
    public function createErrorBlock(DOMElement $fieldBlock, FieldInterface $field)
    {
        $errorBlock = null;
        if (!$field->isValid() && $this->displayErrors === true) {
            $fieldBlock->setAttribute('class', 'form-group has-error');
            $errorBlock = $this->errorRenderer->render($field);
        }
        return $errorBlock;
    }

    /**
     * @param $formGroup
     * @param $field
     * @return DOMElement
     */
    public function createLabelBlock(FieldInterface $field)
    {
        $label = $this->dom->createElement('label');
        $label->setAttribute('for', $field->getId());
        $label->textContent = $field->getLabel();
        return $label;
    }

    /**
     * @return DOMElement
     */
    public function createFieldBlock()
    {
        $formGroup = $this->dom->createElement('div');
        $formGroup->setAttribute('class', 'form-group');
        return $formGroup;
    }

    /**
     * @param FieldInterface $field
     * @return DOMElement
     */
    public function createElement(FieldInterface $field)
    {
        $element = $this->dom->createElement($field->getTag());

        foreach ($field->getAttributes() as $key => $value) {
            $element->setAttribute($key, $value);
        }
        return $element;
    }
}