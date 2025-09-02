<?php

declare(strict_types=1);

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Field\Transformer\DateTimeTransformer;

class DateTime extends Text
{
    public function __construct(string $name, private string $dateFormat = 'Y-m-d H:i:s')
    {
        parent::__construct($name);
    }

    public function init(): void
    {
        parent::init();
        $this->setAttribute('type', 'datetime-local');
        $this->setAttribute('placeholder', 'Enter a date and time..');
        $this->setTransformer(new DateTimeTransformer($this->dateFormat));
    }
}
