<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Field\Radio;
use Del\Form\Form;

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
        $this->assertEquals('<form name="radiotest" method="post" id="radiotest"><div class="form-group"><label for="">Choose</label><div class="radio"><label for=""><input type="radio" name="choose" value="hello">Choose</label></div></div></form>'."\n", $html);
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
        $this->assertEquals('<form name="radiotest" method="post" id="radiotest"><div class="form-group"><label for="">Choose</label><div class="radio"><label for=""><input type="radio" name="choose" value="hello">Choose</label></div><div class="radio"><label for=""><input type="radio" name="choose" value="goodbye">Something</label></div></div></form>'."\n", $html);
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
        $this->assertEquals(''."\n", $html);
    }
}