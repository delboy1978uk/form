<?php

declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use DOMDocument;

interface FieldRendererInterface
{
    public function render(DOMDocument $dom, FieldInterface $field): mixed;
}
