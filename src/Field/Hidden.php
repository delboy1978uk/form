<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Filter\Adapter\FilterAdapterZf;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

class Hidden extends FieldAbstract
{
    public function getTag(): string
    {
        return 'input';
    }

    public function init(): void
    {
        $this->setAttribute('type', 'hidden');
        $this->setAttribute('class', 'form-control');
        $stringTrim = new FilterAdapterZf(new StringTrim());
        $stripTags = new FilterAdapterZf(new StripTags());
        $this->addFilter($stringTrim);
        $this->addFilter($stripTags);
    }
}
