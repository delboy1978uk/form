<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 22:33
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMDocument;
use DOMElement;

class RadioRender extends FieldRender implements FieldRendererInterface
{
    /**
     * @param DOMDocument $dom
     * @param FieldInterface $field
     * @param bool $displayErrors
     * @return DOMElement
     */
    public function render(DOMDocument $dom, FieldInterface $field, $displayErrors = true)
    {
        return parent::render($dom, $field, $displayErrors);
    }

}