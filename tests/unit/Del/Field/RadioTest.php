<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Field\Radio;
use Del\Form\Field\Text;
use Del\Form\Form;
use Del\Form\Renderer\Field\RadioRender;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class RadioTest extends Test
{
    public function testRadio()
    {
        $form = new Form('radiotest');
        $radio = new Radio('choose');
        $radio->setLabel('Choose');
        $radio->setOptions([
            'hello' => 'Choose',
        ]);
        $form->addField($radio);
        $html = $form->render();
        $this->assertEquals('<form name="radiotest" method="post" id="radiotest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="radio" name="choose" value="hello"><label for="1" class="form-check-label">Choose</label></div></div></form>'."\n", $html);
    }


    public function testMultipleRadiosInGroup()
    {
        $form = new Form('radiotest');
        $radio = new Radio('choose');
        $radio->setLabel('Choose');
        $radio->setOptions([
            'hello' => 'Choose',
            'goodbye' => 'Something',
        ]);
        $form->addField($radio);
        $html = $form->render();
        $this->assertEquals('<form name="radiotest" method="post" id="radiotest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="radio" name="choose" value="hello"><label for="1" class="form-check-label">Choose</label></div><div class="form-check"><input class="form-check-input" type="radio" name="choose" value="goodbye"><label for="2" class="form-check-label">Something</label></div></div></form>'."\n", $html);
    }

    public function testMultipleRadiosInHorizontalForm()
    {
        $form = new Form('radiotest');
        $radio = new Radio('choose');
        $radio->setLabel('Choose');
        $radio->setOptions([
            'hello' => 'Choose',
            'goodbye' => 'Something',
        ]);
        $form->addField($radio);
        $html = $form->render();
        $this->assertEquals('<form name="radiotest" method="post" id="radiotest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check"><input class="form-check-input" type="radio" name="choose" value="hello"><label for="1" class="form-check-label">Choose</label></div><div class="form-check"><input class="form-check-input" type="radio" name="choose" value="goodbye"><label for="2" class="form-check-label">Something</label></div></div></form>'."\n", $html);
    }

    public function testRequiredField()
    {
        $form = new Form('required-text-form');
        $text = new Radio('text');
        $text->setRequired(true);
        $form->addField($text);
        $this->assertFalse($form->isValid());
        $text->setValue(['something']);
        $this->assertTrue($form->isValid());
    }

    public function testRendererThrowsException()
    {
        $form = new Form('checkboxtest');
        $text = new Text('bang');
        $text->setRenderer(new RadioRender());
        $form->addField($text);
        $this->expectException('InvalidArgumentException');
        $form->render();
    }

    public function testRendererThrowsExceptionWithNoOptions()
    {
        $form = new Form('checkboxtest');
        $checkbox = new Radio('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setRenderInline(true);
        $form->addField($checkbox);
        $this->expectException('LogicException');
        $form->render();
    }

    public function testRendererInline()
    {
        $form = new Form('checkboxtest');
        $checkbox = new Radio('choose');
        $checkbox->setLabel('Choose');
        $checkbox->setRenderInline(true);
        $checkbox->setOptions([
            1 => 'hello',
            2 => 'hello',
            3 => 'hello',
        ]);
        $checkbox->setValue(3);
        $form->addField($checkbox);
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"><div class="form-group" id="choose-form-group"><label for="">Choose</label><div class="form-check-inline"><input class="form-check-input" type="radio" name="choose" value="1"><label for="1" class="form-check-label">hello</label></div><div class="form-check-inline"><input class="form-check-input" type="radio" name="choose" value="2"><label for="2" class="form-check-label">hello</label></div><div class="form-check-inline"><input class="form-check-input" type="radio" name="choose" value="3" checked><label for="3" class="form-check-label">hello</label></div></div></form>'."\n", $html);
    }

    public function testRenderRadioWithValue()
    {
        $form = new Form('choose');
        $radio = new Radio('selection');
        $radio->setOptions([
            'MCD' => 'McDonalds',
            'BK' => 'Burger King',
            'Q' => 'Quick',
        ]);
        $form->addField($radio);
        $form->populate([
            'selection' => 'Q',
        ]);
        $html = $form->render();
        $this->assertEquals('<form name="choose" method="post" id="choose"><div class="form-group" id="selection-form-group"><label for=""></label><div class="form-check"><input class="form-check-input" type="radio" name="selection" value="MCD"><label for="1" class="form-check-label">McDonalds</label></div><div class="form-check"><input class="form-check-input" type="radio" name="selection" value="BK"><label for="2" class="form-check-label">Burger King</label></div><div class="form-check"><input class="form-check-input" type="radio" name="selection" value="Q" checked><label for="3" class="form-check-label">Quick</label></div></div></form>'."\n", $html);
    }
}