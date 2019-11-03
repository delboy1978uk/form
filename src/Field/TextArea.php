<?php

namespace Del\Form\Field;

use Del\Form\Filter\Adapter\FilterAdapterZf;
use Del\Form\Renderer\Field\TextAreaRender;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;

class TextArea extends FieldAbstract
{
    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->getAttribute('placeholder');
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder(string $placeholder): void
    {
        $this->setAttribute('placeholder', $placeholder);
    }
}