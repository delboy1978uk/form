<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Filter\Adapter\FilterAdapterZf;
use Del\Form\Renderer\Field\TextAreaRender;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

class TextArea extends FieldAbstract
{
    public function getTag(): string
    {
        return 'textarea';
    }

    public function init()
    {
        $this->setAttribute('type', 'text');
        $this->setAttribute('class', 'form-control');
        $stringTrim = new FilterAdapterZf(new StringTrim());
        $stripTags = new FilterAdapterZf(new StripTags());
        $this->addFilter($stringTrim);
        $this->addFilter($stripTags);
        $this->setRenderer(new TextAreaRender());
    }

    public function getPlaceholder(): string
    {
        return $this->getAttribute('placeholder');
    }

    public function setPlaceholder(string $placeholder): void
    {
        $this->setAttribute('placeholder', $placeholder);
    }
}
