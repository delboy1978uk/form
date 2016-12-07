<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 22:33
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMElement;

class RadioRender extends AbstractFieldRender implements FieldRendererInterface
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
        $labelBlock->appendChild($element);
        $fieldBlock->appendChild($labelBlock);
        if ($errorBlock) {
            $fieldBlock->appendChild($errorBlock);
        }
        return $fieldBlock;
    }

}