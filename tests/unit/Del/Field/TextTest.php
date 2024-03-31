<?php

namespace DelTesting\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Form;
use Del\Form\Field\Text;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class TextTest extends Unit
{
    public function testRequiredField()
    {
        $form = new Form('required-text-form');
        $text = new Text('text');
        $text->setRequired(true);
        $form->addField($text);
        $this->assertFalse($form->isValid());
        $text->setValue('something');
        $this->assertTrue($form->isValid());
        $text->setValue(null);
        $text->setRequired(false);
        $this->assertTrue($form->isValid());

    }
}
