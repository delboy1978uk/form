<?php

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMDocument;
use DOMElement;

interface FieldRendererInterface
{

    /**
     * @param DOMDocument $dom
     * @param FieldInterface $field
     * @return DOMElement
     */
    public function render(DOMDocument $dom, FieldInterface $field);
}