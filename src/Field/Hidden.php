<?php

namespace Del\Form\Field;

use Del\Form\Filter\Adapter\FilterAdapterZf;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

class Hidden extends FieldAbstract
{
    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'input';
    }


    public function init()
    {
        $this->setAttribute('type', 'hidden');
        $this->setAttribute('class', 'form-control');
        $stringTrim = new FilterAdapterZf(new StringTrim());
        $stripTags = new FilterAdapterZf(new StripTags());
        $this->addFilter($stringTrim);
        $this->addFilter($stripTags);
    }
}