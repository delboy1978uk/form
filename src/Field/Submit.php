<?php

declare(strict_types=1);

namespace Del\Form\Field;

class Submit extends FieldAbstract
{

    public function getTag(): string
    {
        return 'input';
    }

    public function init()
    {
        $this->setAttribute('type', 'submit');
    }

    public function __construct(string $name, ?string $value = null)
    {
        $value = $value === null ? $name : $value;
        parent::__construct($name, $value);
        $this->setClass('btn btn-primary');
    }
}
