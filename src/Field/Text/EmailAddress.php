<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 17:24
 */

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
        $emailAddressValidator = new ValidatorAdapterZf(new EmailValidator());
        $this->addValidator($emailAddressValidator);
    }
}