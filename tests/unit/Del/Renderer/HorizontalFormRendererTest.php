<?php

namespace DelTesting\Form\Renderer;

use Codeception\TestCase\Test;

use Del\Form\Form;
use Del\Form\Renderer;
use Del\Form\Renderer\HorizontalFormRenderer;
use Del\Form\Field\CheckBox;
use Del\Form\Field\Radio;
use Del\Form\Field\Submit;
use Del\Form\Field\Text;

class HorizontalFormRendererTest extends Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    /**
     * Here we swap over the form renderer and it should
     * render a form as per the example here
     * @see http://getbootstrap.com/css/#forms-horizontal
     */
    public function testRenderHorizontalForm()
    {
        // Set up the form
        $form = new Form('test');
        $renderer = new HorizontalFormRenderer();
        $form->setFormRenderer($renderer);

        $email = new Text\EmailAddress('email');
        $email->setId('email');
        $email->setLabel('Email');
        $email->setPlaceholder('Email');
        $password = new Text\Password('password');
        $password->setId('password');
        $password->setLabel('Password');
        $password->setPlaceholder('Password');
        $remember = new CheckBox('remember');
        $remember->setId('remember');
        $remember->setLabel('Remember me');
        $submit = new Submit('submit');
        $submit->setValue('Sign in');

        $form->addField($email)
            ->addField($password)
            ->addField($remember)
            ->addField($submit);

        // Render the form
        $html = $form->render();

        $this->assertEquals('<form class="form-horizontal" name="test" method="post" id="test"><div class="form-group"><label for="email" class="col-sm-2 control-label">Email</label><div class="col-sm-10"><input name="email" type="email" class="form-control" id="email" placeholder="Email"></div></div><div class="form-group"><label for="password" class="col-sm-2 control-label">Password</label><div class="col-sm-10"><input name="password" type="password" class="form-control" id="password" placeholder="Password"></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-10"><div class="checkbox"><label for="remember"><input name="remember" type="checkbox" id="remember">Remember me</label></div></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-10"><input name="submit" value="Sign in" type="submit" class="btn btn-primary"></div></div></form>'."\n", $html);
    }
}
