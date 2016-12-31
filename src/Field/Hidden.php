<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field;

use Del\Form\Filter\Adapter\FilterAdapterZf;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;

class Hidden extends FieldAbstract
{
    /**
     * @return string
     */
    public function getTag()
    {
        return 'input';
    }


    public function init()
    {
        $this->setAttribute('type', 'hidden');
        $this->setAttribute('class', 'form-control');
        $stringTrim = new FilterAdapterZf(new StringTrim());
        $stripTags = new FilterAdapterZf(new StripTags());
        $this->addFilter($stringTrim)
            ->addFilter($stripTags);
    }
}