<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Form;
use Del\Form\Field\TextArea;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class TextAreaTest extends Test
{

    public function testRenderTextArea()
    {
        $form = new Form('required-text-form');
        $text = new TextArea('text');
        $text->setPlaceholder('Type something..');
        $text->setValue('hello');
        $form->addField($text);
        $html = $form->render();
        $this->assertEquals('Type something..', $text->getPlaceholder());
        $this->assertEquals('<form name="required-text-form" method="post" id="required-text-form"><div class="form-group"><label for=""></label><textarea name="text" class="form-control" placeholder="Type something..">hello</textarea></div></form>'."\n", $html);
    }
}