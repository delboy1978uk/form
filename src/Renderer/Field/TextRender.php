<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 21:08
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMElement;

class TextRender extends AbstractFieldRender implements FieldRendererInterface
{
    /**
     * @param FieldInterface $field
     * @param DOMElement $fieldBlock
     * @param DOMElement $labelBlock
     * @param DOMElement $element
     * @param DOMElement|null $errorBlock
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $fieldBlock, DOMElement $labelBlock, DOMElement $element, DOMElement $errorBlock = null)
    {
        $fieldBlock->appendChild($labelBlock);
        $fieldBlock->appendChild($element);
        if ($errorBlock) {
            $fieldBlock->appendChild($errorBlock);
        }
        return $fieldBlock;
    }

}