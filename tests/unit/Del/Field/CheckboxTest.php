<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Field\CheckBox;
use Del\Form\Form;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class CheckboxTest extends Test
{
    public function testMultipleCheckBoxsInHorizontalForm()
    {
        $form = new Form('checkboxtest');
        $checkbox = new CheckBox('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setOptions([
            'hello' => 'Choose',
            'goodbye' => 'Something',
        ]);

        $form->addField($checkbox);
        $data = $form->getValues();

        $this->assertArrayNotHasKey('choose', $data);
        $this->assertTrue(is_array($data['choose']));
        $this->assertTrue($data['choose']['hello']);
    }

    public function testRequiredCheckbox()
    {
        $form = new Form('radiotest');
        $checkbox = new CheckBox('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setOptions([
            'hello' => 'Choose',
            'goodbye' => 'Something',
        ]);
        $checkbox->setRequired(true);
        $form->addField($checkbox);
        $this->assertFalse($form->isValid());
        $checkbox->checkValue('goodbye');
        $this->assertTrue($form->isValid());
    }
}