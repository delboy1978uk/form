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

    /**
     * Submit constructor.
     * @param $name
     * @param null $value
     */
    public function __construct(string $name, string $value = null)
    {
        $value = is_null($value) ? $name : $value;
        parent::__construct($name, $value);
        $this->setClass('btn btn-primary');
    }
}
