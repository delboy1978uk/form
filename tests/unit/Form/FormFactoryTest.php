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
        $image = realpath(__DIR__ . '/../../_data/fol.gif');
        $_POST = [
            'file' => $image,
            'submit' => 'submit',
        ];
        $_FILES = [
            'file' => [
                'name' => 'fol.gif',
                'type' => 'image/gif',
                'tmp_name' => $image,
                'error' => 0,
                'size' => 10363,
            ],
        ];

        $user = new TestEntity();
        $factory = new FormFactory();
        $form = $factory->createFromEntity('user', $user);
        $this->assertInstanceOf(FormInterface::class, $form);
        $fields = $form->getFields();
        $this->assertCount(13, $fields);
        $this->assertInstanceOf(FieldInterface::class, $form->getField('id'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('email'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('password'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('submit'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('isAdmin'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('price'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('dateTime'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('hidden'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('blurb'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('file'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('multiselect'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('select'));
        $this->assertInstanceOf(FieldInterface::class, $form->getField('radio'));
        $this->assertTrue($form->isValid());
    }
}
