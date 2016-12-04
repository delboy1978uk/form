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
        $formGroup = $this->dom->createElement('div');
        $formGroup->setAttribute('class', 'form-group');

        $label = $this->dom->createElement('label');
        $label->setAttribute('for', $field->getId());
        $label->textContent = $field->getLabel();

        $formField = $this->createElement($field);

        $formGroup->appendChild($label);
        $formGroup->appendChild($formField);

        if (!$field->isValid() && $this->displayErrors === true) {
            $formGroup = $this->createErrorBlock($formGroup, $field);
        }

        return $formGroup;
    }

    /**
     * @param FieldInterface $field
     * @return DOMElement
     */
    abstract public function createElement(FieldInterface $field);

    /**
     * @param $formGroup
     * @param $field
     * @return DOMElement
     */
    public function createErrorBlock(DOMElement $formGroup, FieldInterface $field)
    {
        return $this->errorRenderer->render($formGroup, $field);
    }


}