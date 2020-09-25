<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Field\Text\FloatingPoint;
use Del\Form\Form;

class FloatTest extends Test
{
    public function testRequiredField()
    {
        $float = new FloatingPoint('float');
        $float->setRequired(true);
        $float->setValue('1');
//        $this->assertTrue($form->isValid());
        $float->setValue('1.5');
//        $this->assertTrue($form->isValid());
        $float->setValue('two');
        $this->assertFalse($float->isValid());

    }
}