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
     * @param FieldInterface $field
     * @param DOMElement $fieldBlock
     * @param DOMElement $labelBlock
     * @param DOMElement $element
     * @param DOMElement|null $errorBlock
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $fieldBlock, DOMElement $labelBlock, DOMElement $element, DOMElement $errorBlock = null);

    /**
     * @return DOMElement
     */
    public function render(DOMDocument $dom, FieldInterface $field, $displayErrors);
}