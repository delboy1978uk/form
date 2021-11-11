<?php

namespace Del\Form\Field;

use Del\Form\Renderer\Field\SelectRender;
use Del\Form\Traits\HasOptionsTrait;

class MultiSelect extends FieldAbstract implements ArrayValueInterface
{
    use HasOptionsTrait;

    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'select';
    }

    public function init(): void
    {
        $this->setAttribute('type', 'text');
        $this->setAttribute('multiple', '');
        $this->setAttribute('class', 'form-control');
        $this->setRenderer(new SelectRender());
    }
}
