<?php

namespace Del\Test\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Form;
use Del\Form\Field\Hidden;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class HiddenTest extends Unit
{
    public function testRequiredField()
    {
        $form = new Form('required-text-form');
        $hidden = new Hidden('secret');
        $hidden->setValue('hahaha');
        $form->addField($hidden);
        $html = $form->render();
        $this->assertEquals('<form name="required-text-form" method="post" id="required-text-form"><div class="form-group" id="secret-form-group"><label for=""></label><input name="secret" type="hidden" class="form-control" value="hahaha"></div></form>'."\n", $html);

    }
}
