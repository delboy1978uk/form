<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 20:47
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMDocument;
use DOMElement;

interface FieldRendererInterface
{
    /**
     * @param DOMDocument $dom
     * @param FieldInterface $field
     * @param bool $displayErrors
     * @return DOMElement
     */
    public function render(DOMDocument $dom, FieldInterface $field, $displayErrors = true);
}