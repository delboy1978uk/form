<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 20:47
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMElement;

interface FieldRendererInterface
{
    /**
     * @param DOMElement $fieldBlock
     * @param DOMElement $labelBlock
     * @param DOMElement $element
     * @param DOMElement|null $errorBlock
     * @return mixed
     */
    public function renderFieldBlock(FieldInterface $field, DOMElement $fieldBlock, DOMElement $labelBlock, DOMElement $element, DOMElement $errorBlock = null);
}