<?php

declare(strict_types=1);

namespace unit;

use Del\Form\Field\Attributes\Field;

class TestEntity
{
    #[Field('integer|required')]
    public int $id = 6;

    #[Field('text|required|min:2|max:50')]
    public string $email = 'man@work.com';

    #[Field('email|required|min:2|max:50')]
    public string $password = 'xxxx';
}
