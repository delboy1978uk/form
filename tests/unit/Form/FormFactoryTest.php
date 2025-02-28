<?php

namespace Del\Test\Form;

use Codeception\Test\Unit;
use Del\Form\Factory\FormFactory;
use Del\Form\FormInterface;

class FormFactoryTest extends Unit
{
    public function testFactory(): void
    {
        $user = new TestEntity();
        $factory = new FormFactory();
        $form = $factory->createFromEntity('user', $user);
        $this->assertInstanceOf(FormInterface::class, $form);
    }
}
