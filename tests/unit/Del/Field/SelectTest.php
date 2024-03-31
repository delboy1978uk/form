<?php

namespace DelTesting\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Field\Submit;
use Del\Form\Field\Text;
use Del\Form\Field\Select;
use Del\Form\Form;
use Del\Form\Renderer\Field\SelectRender;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class SelectTest extends Unit
{
    public function testSelect()
    {
        $form = new Form('dropdown');
        $select = new Select('selection');
        $select->setOption(1, 'hello');
        $select->setOption(2, 'world');
        $form->addField($select);
        $html = $form->render();
        $this->assertEquals('<form name="dropdown" method="post" id="dropdown"><div class="form-group" id="selection-form-group"><label for=""></label><select name="selection" type="text" class="form-control"><option value="1">hello</option><option value="2">world</option></select></div></form>'."\n", $html);
    }

    public function testSelectThrowsException()
    {
        $form = new Form('dropdown');
        $text = new Text('selection');
        $text->setRenderer(new SelectRender());
        $form->addField($text);
        $this->expectException('InvalidArgumentException');
        $form->render();
    }


    public function testGetSetOptions()
    {
        $form = new Form('dropdown');
        $select = new Select('selection');
        $select->setOptions([
            1 => 'hello',
            2 => 'world',
        ]);
        $options = $select->getOptions();
        $this->assertArrayHasKey(1, $options);
        $this->assertArrayHasKey(2, $options);
        $this->assertEquals('hello', $options[1]);
        $this->assertEquals('world', $options[2]);
        $this->assertEquals('world', $select->getOption(2));
        $form->addField($select);
        $html = $form->render();
        $this->assertEquals('<form name="dropdown" method="post" id="dropdown"><div class="form-group" id="selection-form-group"><label for=""></label><select name="selection" type="text" class="form-control"><option value="1">hello</option><option value="2">world</option></select></div></form>'."\n", $html);
    }


    public function testRenderSelectWithValue()
    {
        $form = new Form('choose');
        $select = new Select('selection');
        $select->setOptions([
            'MCD' => 'McDonalds',
            'BK' => 'Burger King',
            'Q' => 'Quick',
        ]);
        $submit = new Submit('submit');
        $form->addField($select);
        $form->addField($submit);
        $form->populate([
            'selection' => 'Q',
        ]);
        $html = $form->render();
        $this->assertEquals('<form name="choose" method="post" id="choose"><div class="form-group" id="selection-form-group"><label for=""></label><select name="selection" type="text" class="form-control" value="Q"><option value="MCD">McDonalds</option><option value="BK">Burger King</option><option value="Q" selected>Quick</option></select></div><div class="form-group" id="submit-form-group"><label for=""></label><input name="submit" value="submit" type="submit" class="btn btn-primary"></div></form>'."\n", $html);
    }
}
