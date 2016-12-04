<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 23:37
 */

namespace Del\Form\Renderer\Field\Error;

use Del\Form\Field\FieldInterface;
use DOMElement;


interface ErrorRendererInterface
{
    /**
     * @param DOMElement $div
     * @param FieldInterface $field
     * @return DOMElement
     */
    public function render(DOMElement $div, FieldInterface $field);
}