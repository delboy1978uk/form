<?php
/**
 * User: delboy1978uk
 * Date: 29/11/2016
 * Time: 19:44
 */

namespace Del\Form\Renderer;

use Del\Form\Renderer\Error\HorizontalFormErrorRender;
use DOMElement;
use DOMNode;
use DOMText;

class HorizontalFormRenderer extends AbstractFormRenderer implements FormRendererInterface
{
    public function __construct()
    {
        parent::__construct();

        // Add horizontal form class
        $this->form->setAttribute('class', 'form-horizontal');
        $this->errorRenderer = new HorizontalFormErrorRender($this->dom);
    }

    /**
     * @return DOMElement
     */
    public function renderFieldLabel()
    {
        $label = $this->createLabelElement();
        $label->setAttribute('class', 'col-sm-2 control-label');
        $text = new DOMText($this->field->getLabel());
        $label->appendChild($text);
        return $label;
    }

    /**
     * @return DOMElement
     */
    public function renderFieldBlock()
    {
        $class = $this->block->getAttribute('class').'form-group';
        $this->block->setAttribute('class', $class);

        $div = $this->dom->createElement('div');
        $div->setAttribute('class', 'col-sm-10');

        $this->processField($div);

        $this->block->appendChild($div);

        if (!is_null($this->errors)) {
            $this->block->appendChild($this->errors);
        }

        return $this->block;
    }

    /**
     * @param DOMElement $div
     */
    private function processField(DOMElement $div)
    {
        switch (get_class($this->field)) {
            case 'Del\Form\Field\Submit':
                $div->appendChild($this->element);
                $div->setAttribute('class', 'col-sm-offset-2 col-sm-10');
                break;
            case 'Del\Form\Field\Radio':
                $radioDiv = $this->surroundInDiv($this->element, 'radio');
                $this->block->appendChild($this->label);
                $div->appendChild($radioDiv);
                break;
            case 'Del\Form\Field\CheckBox':
                $checkboxDiv = $this->surroundInDiv($this->element, 'checkbox');
                $this->block->appendChild($this->label);
                $div->appendChild($checkboxDiv);
                break;
            default:
                $this->block->appendChild($this->label);
                $div->appendChild($this->element);
        }
    }

    /**
     * Surround an element in a div with a given class
     *
     * @param DOMNode $element
     * @param $class
     * @return DOMElement
     */
    private function surroundInDiv(DOMNode $element, $class)
    {
        $div = $this->dom->createElement('div');
        $div->setAttribute('class', $class);
        $div->appendChild($element);
        return $div;
    }

}