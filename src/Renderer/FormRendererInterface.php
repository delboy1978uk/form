<?php

declare(strict_types=1);

namespace Del\Form\Renderer;

use Del\Form\FormInterface;
use DOMElement;

interface FormRendererInterface
{
    public function render(FormInterface $form, $displayErrors = true): string;
    public function renderFieldLabel(): DOMElement;
    public function renderFieldBlock(): DOMElement;
}
