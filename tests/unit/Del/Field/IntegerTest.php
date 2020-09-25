<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Field\Text\Integer;
use Del\Form\Form;

class IntegerTest extends Test
{
    public function testRequiredField()
    {
        $form = new Form('required-integer-form');
        $int = new Integer('text');
        $form->addField($int);
        $int->setRequired(true);
        $int->setValue('1');
        $this->assertTrue($form->isValid());
        $int->setValue('1.5');
        $this->assertFalse($form->isValid());
        $int->setValue('two');
        $this->assertFalse($form->isValid());

    }
}