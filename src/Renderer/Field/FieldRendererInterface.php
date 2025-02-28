<?php

declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMDocument;
use DOMElement;

interface FieldRendererInterface
{
    public function render(DOMDocument $dom, FieldInterface $field): DOMElement;
}
