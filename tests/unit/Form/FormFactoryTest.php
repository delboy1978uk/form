<?php

namespace Del\Test\Form;

use Codeception\Test\Unit;
use Del\Form\Factory\FormFactory;
use Del\Form\Field\FieldInterface;
use Del\Form\FormInterface;

class FormFactoryTest extends Unit
{
    public function testFactory(): void
    {
        $user = new TestEntity();
        $factory = new FormFactory();
        $form = $factory->createFromEntity('user', $user);
        $this->assertInstanceOf(FormInterface::class, $form);
        $fields = $form->getFields();
        $this->assertCount(3, $fields);
        $this->assertInstanceOf(FieldInterface::class, $form->getField('id'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('email'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('password'));
        $this->assertTrue($form->isValid());
    }
}
