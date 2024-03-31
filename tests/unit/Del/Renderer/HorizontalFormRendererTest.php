<?php

namespace DelTesting\Form\Renderer;

use Codeception\Test\Unit;

use Del\Form\Form;
use Del\Form\Renderer;
use Del\Form\Renderer\HorizontalFormRenderer;
use Del\Form\Field\CheckBox;
use Del\Form\Field\Radio;
use Del\Form\Field\Submit;
use Del\Form\Field\Text;

class HorizontalFormRendererTest extends Unit
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
        $remember->setOptions([
            'remember' => 'Remember Me',
        ]);
        $submit = new Submit('submit');
        $submit->setValue('Sign in');

        $form->addField($email);
        $form->addField($password);
        $form->addField($remember);
        $form->addField($submit);

        // Render the form
        $html = $form->render();

        $this->assertEquals('<form class="form-horizontal" name="test" method="post" id="test"><div class=" form-group row"><label for="email" class="col-sm-2 col-md-3 control-label">Email</label><div class="col"><input name="email" type="email" class="form-control" placeholder="Email" id="email"></div></div><div class=" form-group row"><label for="password" class="col-sm-2 col-md-3 control-label">Password</label><div class="col"><input name="password" type="password" class="form-control" id="password" placeholder="Password"></div></div><div class=" form-group row"><label for="remember" class="col-sm-2 col-md-3 control-label">Remember me</label><div class="col"><div class="checkbox"><div class="form-check"><input class="form-check-input" type="checkbox" name="remember" value="remember"><label for="remember1" class="form-check-label">Remember Me</label></div></div></div></div><div class=" form-group row"><div class="col"><input name="submit" value="Sign in" type="submit" class="btn btn-primary"></div></div></form>'."\n", $html);
    }

    public function testRenderHorizontalFormWithErrors()
    {
        // Set up the form
        $form = new Form('test');
        $renderer = new HorizontalFormRenderer();
        $form->setFormRenderer($renderer);

        $email = new Text\EmailAddress('email');
        $email->setId('email');
        $email->setLabel('Email');
        $email->setPlaceholder('Email');
        $email->setCustomErrorMessage('wtf');
        $email->setRequired(true);
        $password = new Text\Password('password');
        $password->setId('password');
        $password->setLabel('Password');
        $password->setPlaceholder('Password');
        $password->setRequired(true);
        $remember = new Radio('choose');
        $remember->setId('choose');
        $remember->setLabel('Choose');
        $remember->setOptions([
            'choose' => 'Choose Me!',
            'choose2' => 'Or Me!',
        ]);
        $submit = new Submit('submit');
        $submit->setValue('Sign in');

        $form->addField($email);
        $form->addField($password);
        $form->addField($remember);
        $form->addField($submit);
        $form->populate([]);;

        // Render the form
        $html = $form->render();

        $this->assertEquals('<form class="form-horizontal" name="test" method="post" id="test"><div class="has-error  form-group row"><label for="email" class="col-sm-2 col-md-3 control-label"><span class="text-danger">* </span>Email</label><div class="col"><input name="email" type="email" class="form-control" placeholder="Email" id="email"></div><div class="col-sm-offset-2 col-sm-10"><span class="text-danger">wtf</span></div></div><div class="has-error  form-group row"><label for="password" class="col-sm-2 col-md-3 control-label"><span class="text-danger">* </span>Password</label><div class="col"><input name="password" type="password" class="form-control" id="password" placeholder="Password"></div><div class="col-sm-offset-2 col-sm-10"><span class="text-danger">Value is required and can\'t be empty<br></span></div></div><div class=" form-group row"><label for="choose" class="col-sm-2 col-md-3 control-label">Choose</label><div class="col"><div class="radio"><div class="form-check"><input class="form-check-input" type="radio" name="choose" value="choose"><label for="choose1" class="form-check-label">Choose Me!</label></div><div class="form-check"><input class="form-check-input" type="radio" name="choose" value="choose2"><label for="choose2" class="form-check-label">Or Me!</label></div></div></div></div><div class=" form-group row"><div class="col"><input name="submit" value="Sign in" type="submit" class="btn btn-primary"></div></div></form>'."\n", $html);
    }
}
