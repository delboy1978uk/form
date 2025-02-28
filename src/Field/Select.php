<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Renderer\Field\SelectRender;
use Del\Form\Traits\HasOptionsTrait;

class Select extends FieldAbstract
{
    use HasOptionsTrait;

    public function getTag(): string
    {
        return 'select';
    }

    public function init(): void
    {
        $this->setAttribute('type', 'text');
        $this->setAttribute('class', 'form-control');
        $this->setRenderer(new SelectRender());
    }
}
