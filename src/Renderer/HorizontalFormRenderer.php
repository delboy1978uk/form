<?php
/**
 * User: delboy1978uk
 * Date: 29/11/2016
 * Time: 19:44
 */

namespace Del\Form\Renderer;

use Del\Form\Field\CheckBox;
use Del\Form\Field\Radio;
use Del\Form\Field\Submit;
use DOMElement;
use DOMText;

class HorizontalFormRenderer extends AbstractFormRenderer  implements FormRendererInterface
{
    public function __construct()
    {
        parent::__construct();

        // Add horizontal form class
        $this->form->setAttribute('class', 'form-horizontal');
    }

    /**
     * @return DOMElement
     */
    public function renderFieldLabel()
    {
        $label = $this->dom->createElement('label');
        $label->setAttribute('for', $this->field->getId());
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
        $formGroup = $this->block;
        $formGroup->setAttribute('class', 'form-group');

        $div = $this->dom->createElement('div');
        $div->setAttribute('class', 'col-sm-offset-2 col-sm-10');

        switch (get_class($this->field)) {
            case 'Del\Form\Field\Submit':
                $div->appendChild($this->element);
                break;
            case 'Del\Form\Field\Radio':
                $radioDiv = $this->surroundInDiv($this->element, 'radio');
                $div->appendChild($radioDiv);
                break;
            case 'Del\Form\Field\CheckBox':
                $checkboxDiv = $this->surroundInDiv($this->element, 'checkbox');
                $div->appendChild($checkboxDiv);
                break;
            default:
                $formGroup->appendChild($this->label);
                $div->setAttribute('class', 'col-sm-10');
                $div->appendChild($this->element);
        }

        $formGroup->appendChild($div);

        if (!is_null($this->errors)) {
            $formGroup->appendChild($this->errors);
        }

        return $formGroup;
    }

    /**
     * Surround an element in a div with a given class
     *
     * @param DOMElement $element
     * @param $class
     * @return DOMElement
     */
    private function surroundInDiv(DOMElement $element, $class)
    {
        $div = $this->dom->createElement('div');
        $div->setAttribute('class', $class);
        $div->appendChild($element);
        return $div;
    }

}