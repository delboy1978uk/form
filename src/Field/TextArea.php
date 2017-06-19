<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

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
    public function getTag()
    {
        return 'textarea';
    }


    public function init()
    {
        $this->setAttribute('type', 'text');
        $this->setAttribute('class', 'form-control');
        $stringTrim = new FilterAdapterZf(new StringTrim());
        $stripTags = new FilterAdapterZf(new StripTags());
        $this->addFilter($stringTrim)
            ->addFilter($stripTags);
        $this->setRenderer(new TextAreaRender());
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->getAttribute('placeholder');
    }

    /**
     * @param string $placeholder
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->setAttribute('placeholder', $placeholder);
        return $this;
    }
}