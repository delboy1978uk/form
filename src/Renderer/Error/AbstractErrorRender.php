<?php

declare(strict_types=1);

namespace Del\Form\Renderer\Error;

use DOMDocument;
use Del\Form\Field\FieldInterface;
use Del\Form\Traits\HasDomTrait;

abstract class AbstractErrorRender implements ErrorRendererInterface
{
    use HasDomTrait;

    public function __construct(DOMDocument $dom)
    {
        $this->setDom($dom);
    }

    public function shouldRender(FieldInterface $field): bool
    {
        return !$field->isValid() && ($field->isRequired() || !empty($field->getValue()));
    }
}
