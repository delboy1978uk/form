<?php

namespace DelTesting\Form;

use Codeception\TestCase\Test;
use DelTesting\Form\TestForm;

class FormTest extends Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    /**
     * Check tests are working
     */
    public function testRender()
    {
        $form = new TestForm('test');
        $html = $form->render();
        $this->assertEquals('<form id="test" name = "test" method="post" action=""></form>', $html);
    }


}
