<?php

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Validator\Adapter\ValidatorAdapterZf;
use Zend\Validator\EmailAddress as EmailValidator;

class EmailAddress extends Text
{
    public function init()
    {
        parent::init();
        $this->setAttribute('type', 'email');
        $this->setAttribute('placeholder', 'Enter an email address..');
        $emailAddressValidator = new ValidatorAdapterZf(new EmailValidator());
        $this->addValidator($emailAddressValidator);
    }
}