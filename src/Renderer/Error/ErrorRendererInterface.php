<?php

declare(strict_types=1);

namespace Del\Form\Renderer\Error;

use Del\Form\Field\FieldInterface;

interface ErrorRendererInterface
{
    public function render(FieldInterface $field): mixed;
    public function shouldRender(FieldInterface $field): bool;
}
