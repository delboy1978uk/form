<?php

namespace Del\Test\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Field\Text\Integer;
use Del\Form\Form;

class IntegerTest extends Unit
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
