<?php

declare(strict_types=1);

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Validator\Adapter\ValidatorAdapterZf;
use Laminas\Validator\EmailAddress as EmailValidator;

class EmailAddress extends Text
{
    public function init(): void
    {
        parent::init();
        $this->setAttribute('type', 'email');
        $this->setAttribute('placeholder', 'Enter an email address..');
        $emailAddressValidator = new ValidatorAdapterZf(new EmailValidator());
        $this->addValidator($emailAddressValidator);
    }
}
