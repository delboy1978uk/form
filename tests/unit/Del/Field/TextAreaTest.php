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
    public function testRequiredField()
    {
        $form = new Form('required-text-form');
        $hidden = new TextArea('secret');
        $hidden->setValue('hahaha');
        $form->addField($hidden);
        $html = $form->render();
        $this->assertEquals('<form name="required-text-form" method="post" id="required-text-form"><div class="form-group"><label for=""></label><textarea name="secret" type="text" class="form-control" value="hahaha">hahaha</textarea></div></form>'."\n", $html);

    }
}