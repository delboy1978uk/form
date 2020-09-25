<?php

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Filter\Adapter\FilterAdapterZf;
use Del\Form\Validator\FloatValidator;
use Laminas\Filter\ToFloat;

class FloatingPoint extends Text
{
    public function init()
    {
        parent::init();
        $this->setAttribute('type', 'number');
        $this->setAttribute('placeholder', 'Enter an email address..');
        $toFloatValidator = new FloatValidator();
        $this->addValidator($toFloatValidator);
    }
}