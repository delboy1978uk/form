<?php

declare(strict_types=1);

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Field\Transformer\DateTimeTransformer;

class Date extends Text
{
    public function __construct(string $name, private string $dateFormat = 'Y-m-d')
    {
        parent::__construct($name);
    }

    public function init(): void
    {
        parent::init();
        $this->setAttribute('type', 'date');
        $this->setAttribute('placeholder', 'Enter a date');
        $this->setTransformer(new DateTimeTransformer($this->dateFormat));
    }
}
