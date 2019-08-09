<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Field\CheckBox;
use Del\Form\Field\Text;
use Del\Form\Form;
use Del\Form\Renderer\Field\CheckboxRender;

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
        $checkbox->checkValue('hello');
        $form->addField($checkbox);
        $data = $form->getValues();

        $this->assertArrayHasKey('choose', $data);
        $this->assertTrue(is_array($data['choose']));
        $this->assertTrue($data['choose']['hello']);
    }

    public function testRequiredCheckbox()
    {
        $form = new Form('checkboxtest');
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

    public function testPopulateCheckbox()
    {
        $form = new Form('checkboxtest');
        $checkbox = new CheckBox('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setOptions([
            1 => 'Choose',
            2 => 'Something',
            3 => 'Now',
            4 => 'Or',
            5 => 'Else',
        ]);
        $checkbox->setValue([1, 3, 5]);
        $form->addField($checkbox);
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="1" checked>Choose</label></div><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="2">Something</label></div><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="3" checked>Now</label></div><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="4">Or</label></div><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="5" checked>Else</label></div></div></form>' . "\n", $html);
    }

    public function testUncheckValue()
    {
        $form = new Form('checkboxtest');
        $checkbox = new CheckBox('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setOptions([
            1 => 'Choose',
            2 => 'Something',
            3 => 'Now',
            4 => 'Or',
            5 => 'Else',
        ]);
        $checkbox->setValue([1, 3, 5]);
        $form->addField($checkbox);
        $checkbox->uncheckValue(3);
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="1" checked>Choose</label></div><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="2">Something</label></div><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="3">Now</label></div><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="4">Or</label></div><div class="checkbox"><label for=""><input type="checkbox" name="choose[]" value="5" checked>Else</label></div></div></form>' . "\n", $html);
    }

    public function testRenderCheckboxInline()
    {
        $form = new Form('checkboxtest');
        $checkbox = new CheckBox('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setOptions([
            1 => 'Choose',
            2 => 'Something',
        ]);
        $checkbox->setRenderInline(true);
        $form->addField($checkbox);
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><label for="" class="checkbox-inline"><input type="checkbox" name="choose[]" value="1">Choose</label><label for="" class="checkbox-inline"><input type="checkbox" name="choose[]" value="2">Something</label></div></form>' . "\n", $html);
    }

    public function testRendererThrowsException()
    {
        $form = new Form('checkboxtest');
        $text = new Text('bang');
        $text->setRenderer(new CheckboxRender());
        $form->addField($text);
        $this->expectException('InvalidArgumentException');
        $form->render();
    }

    public function testRendererThrowsExceptionWithNoOptions()
    {
        $form = new Form('checkboxtest');
        $checkbox = new CheckBox('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setRenderInline(true);
        $form->addField($checkbox);
        $this->expectException('LogicException');
        $form->render();
    }


    public function testSingleCheckbox()
    {
        $form = new Form('checkboxtest');
        $checkbox = new CheckBox('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setOptions([
            1 => 'Agree',
        ]);
        $checkbox->setValue(1);
        $form->addField($checkbox);
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="checkbox"><label for=""><input type="checkbox" name="choose" value="1">Agree</label></div></div></form>' . "\n", $html);
    }


    public function testPopulateSingleCheckbox()
    {
        $form = new Form('checkboxtest');
        $checkbox = new CheckBox('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setOptions([
            1 => 'Agree',
        ]);
        $form->addField($checkbox);
        $form->populate([
            'choose' => true
        ]);
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="checkbox"><label for=""><input type="checkbox" name="choose" value="1" checked>Agree</label></div></div></form>' . "\n", $html);
    }
}