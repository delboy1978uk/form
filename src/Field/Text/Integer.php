<?php

declare(strict_types=1);

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Validator\IntegerValidator;

class Integer extends Text
{
    public function init(): void
    {
        parent::init();
        $this->setAttribute('type', 'number');
        $this->setAttribute('placeholder', 'Enter a number');
        $toIntegerValidator = new IntegerValidator();
        $this->addValidator($toIntegerValidator);
    }
}
