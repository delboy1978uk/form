<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Filter\Adapter\FilterAdapterZf;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

class Text extends FieldAbstract
{
    public function getTag(): string
    {
        return 'input';
    }

    public function init()
    {
        $this->setAttribute('type', 'text');
        $this->setAttribute('class', 'form-control');
        $stringTrim = new FilterAdapterZf(new StringTrim());
        $stripTags = new FilterAdapterZf(new StripTags());
        $this->addFilter($stringTrim);
        $this->addFilter($stripTags);
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
