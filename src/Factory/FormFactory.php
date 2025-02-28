<?php

declare(strict_types=1);

namespace Del\Form\Factory;

use Del\Form\Form;
use Del\Form\FormInterface;
use ReflectionClass;

class FormFactory
{
    public function createFromEntity(string $name, object $entity): FormInterface
    {
        $form = new Form($name);
        $mirror = new ReflectionClass($entity);
        $attributes = $mirror->getAttributes();

        foreach ($attributes as $attribute) {

        }

        return $form;
    }
}
