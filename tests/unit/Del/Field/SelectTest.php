<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Field\Select;
use Del\Form\Form;
/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class SelectTest extends Test
{
    public function testSelect()
    {
        $form = new Form('dropdown');
        $select = new Select('selection');
        $select->setOption(1, 'hello');
        $select->setOption(2, 'world');
        $form->addField($select);
        $html = $form->render();
        $this->assertEquals('<form name="dropdown" method="post" id="dropdown"><div class="form-group"><label for=""></label><select name="selection" type="text" class="form-control"><option value="1">hello</option><option value="2">world</option></select></div></form>'."\n", $html);
    }
}