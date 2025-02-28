<?php

declare(strict_types=1);

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;

class Password extends Text
{
    public function init(): void
    {
        parent::init();
        $this->setAttribute('type', 'password');
    }
}
