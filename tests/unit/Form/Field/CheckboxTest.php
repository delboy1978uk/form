<?php

namespace Del\Test\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Field\CheckBox;
use Del\Form\Field\Text;
use Del\Form\Form;
use Del\Form\Renderer\Field\CheckboxRender;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class CheckboxTest extends Unit
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
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="1" checked><label for="1" class="form-check-label">Choose</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="2"><label for="2" class="form-check-label">Something</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="3" checked><label for="3" class="form-check-label">Now</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="4"><label for="4" class="form-check-label">Or</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="5" checked><label for="5" class="form-check-label">Else</label></div></div></form>' . "\n", $html);
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
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="1" checked><label for="1" class="form-check-label">Choose</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="2"><label for="2" class="form-check-label">Something</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="3"><label for="3" class="form-check-label">Now</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="4"><label for="4" class="form-check-label">Or</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="5" checked><label for="5" class="form-check-label">Else</label></div></div></form>' . "\n", $html);

        $checkbox->setValue(1);
        $form->addField($checkbox);
        $checkbox->uncheckValue(1);
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="1"><label for="6" class="form-check-label">Choose</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="2"><label for="7" class="form-check-label">Something</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="3"><label for="8" class="form-check-label">Now</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="4"><label for="9" class="form-check-label">Or</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="5"><label for="10" class="form-check-label">Else</label></div></div><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="1"><label for="11" class="form-check-label">Choose</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="2"><label for="12" class="form-check-label">Something</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="3"><label for="13" class="form-check-label">Now</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="4"><label for="14" class="form-check-label">Or</label></div><div class="form-check"><input class="form-check-input" type="checkbox" name="choose[]" value="5"><label for="15" class="form-check-label">Else</label></div></div></form>' . "\n", $html);
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
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check-inline"><input class="form-check-input" type="checkbox" name="choose[]" value="1"><label for="1" class="form-check-label">Choose</label></div><div class="form-check-inline"><input class="form-check-input" type="checkbox" name="choose[]" value="2"><label for="2" class="form-check-label">Something</label></div></div></form>' . "\n", $html);
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
        $form->addField($checkbox);
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="checkbox" name="choose" value="1"><label for="1" class="form-check-label">Agree</label></div></div></form>' . "\n", $html);
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
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="checkbox" name="choose" value="1" checked><label for="1" class="form-check-label">Agree</label></div></div></form>' . "\n", $html);
    }


    public function testEmptyCheckbox()
    {
        $form = new Form('checkboxtest');
        $checkbox = new CheckBox('choose');
        $checkbox->setRequired(true);
        $checkbox->setLabel('Choose');
        $checkbox->setOptions([
            1 => 'Agree',
        ]);
        $form->addField($checkbox);
        $form->populate([]);
        $this->assertFalse($form->isValid());
    }
}
