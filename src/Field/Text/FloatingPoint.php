<?php

declare(strict_types=1);

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Validator\FloatValidator;

class FloatingPoint extends Text
{
    public function init(): void
    {
        parent::init();
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', '0.01');
        $this->setAttribute('placeholder', 'Enter a numeric value..');
        $toFloatValidator = new FloatValidator();
        $this->addValidator($toFloatValidator);
    }
}
