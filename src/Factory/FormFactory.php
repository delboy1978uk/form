<?php

declare(strict_types=1);

namespace Del\Form\Factory;

use Del\Form\Form;
use Del\Form\FormInterface;

class FormFactory
{
    public function createFromEntity(string $name, object $entity): FormInterface
    {
        $form = new Form($name);
    }
}
