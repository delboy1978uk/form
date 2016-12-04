<?php
/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 00:10
 */

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMElement;

class FieldRender extends AbstractFieldRender implements FieldRendererInterface
{
    /**
     * @param FieldInterface $field
     * @return DOMElement
     */
    public function createElement(FieldInterface $field)
    {
        $child = $this->dom->createElement($field->getTag());

        foreach ($field->getAttributes() as $key => $value) {
            $child->setAttribute($key, $value);
        }

        return $child;
    }
}