<?php

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Validator\IntegerValidator;

class Integer extends Text
{
    public function init()
    {
        parent::init();
        $this->setAttribute('type', 'email');
        $this->setAttribute('placeholder', 'Enter an email address..');
        $toIntegerValidator = new IntegerValidator();
        $this->addValidator($toIntegerValidator);
    }
}