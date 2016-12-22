<?php

namespace DelTesting\Form;

use Codeception\TestCase\Test;
use Del\Form\Collection\FilterCollection;
use Del\Form\Collection\ValidatorCollection;
use Del\Form\Form;
use Del\Form\Field\CheckBox;
use Del\Form\Field\Radio;
use Del\Form\Field\Submit;
use Del\Form\Field\Text;
use Del\Form\Field\Text\EmailAddress;
use Del\Form\Field\Text\Password;
use Del\Form\Filter\Adapter\FilterAdapterZf;
use Del\Form\Renderer\Field\TextRender;
use Del\Form\Validator\Adapter\ValidatorAdapterZf;
use Zend\Filter\StripTags;
use Zend\Filter\UpperCaseWords;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class FormTest extends Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    public function testGetSetId()
    {
        $form = new Form('test');
        $form->setId('testId');
        $this->assertEquals('testId', $form->getId());
    }

    public function testGetSetClass()
    {
        $form = new Form('test');
        $form->setClass('some-class');
        $this->assertEquals('some-class', $form->getClass());
    }

    public function testGetSetMethod()
    {
        $form = new Form('test');
        $form->setMethod(Form::METHOD_GET);
        $this->assertEquals(Form::METHOD_GET, $form->getMethod());
    }

    public function testGetSetAction()
    {
        $form = new Form('test');
        $form->setAction('/some/url');
        $this->assertEquals('/some/url', $form->getAction());
    }

    public function testGetSetEncType()
    {
        $form = new Form('test');
        $form->setEncType(Form::ENC_TYPE_MULTIPART_FORM_DATA);
        $this->assertEquals(Form::ENC_TYPE_MULTIPART_FORM_DATA, $form->getEncType());
    }

    public function testGetSetDisplayErrors()
    {
        $form = new Form('test');
        $form->setDisplayErrors(false);
        $this->assertFalse($form->isDisplayErrors());
        $form->setDisplayErrors(true);
        $this->assertTrue($form->isDisplayErrors());
    }

    /**
     * Check tests are working
     */
    public function testRender()
    {
        $form = new Form('test');
        $form->setAction('/here');
        $form->setId('testId');
        $form->setClass('random-class');
        $form->setEncType(Form::ENC_TYPE_MULTIPART_FORM_DATA);
        $form->setMethod(Form::METHOD_GET);
        $html = $form->render();

        $this->assertEquals('/here', $form->getAction());
        $this->assertEquals('testId', $form->getId());
        $this->assertEquals('random-class', $form->getClass());
        $this->assertEquals('<form name="test" method="get" action="/here" id="testId" class="random-class" enctype="multipart/form-data"></form>'."\n", $html);
    }

    public function testTextField()
    {
        $form = new Form('test');
        $text = new Text('username', 'delboy1978uk');
        $text->setId('user');
        $form->addField($text);
        $attributes = $text->getAttributes();
        $attributes['data-target'] = '#target';
        $text->setAttributes($attributes);
        $values = $form->getValues();
        $html = $form->render();
        $this->assertEquals('<form name="test" method="post" id="test"><div class="form-group"><label for="user"></label><input name="username" value="delboy1978uk" type="text" class="form-control" id="user" data-target="#target"></div></form>'."\n", $html);
        $this->assertArrayHasKey('username', $values);
        $this->assertEquals('delboy1978uk', $values['username']);
    }

    public function testFindFieldInCollectionByName()
    {
        $form = new Form('test');
        $text = new Text('username', 'delboy1978uk');
        $text->setId('user');
        $class = $text->getClass().' extra-class';
        $text->setClass($class);
        $form->addField($text);
        $fields = $form->getFields();
        $field = $fields->findByName('username');
        $this->assertInstanceOf('Del\Form\Field\Text', $field);
        $this->assertEquals('form-control extra-class', $field->getClass());
        $this->assertEquals('user', $field->getId());
        $this->assertEquals('delboy1978uk', $field->getValue());
        $this->assertEquals('input', $field->getTag());

        $form = new Form('test');
        $text = new Text('username', 'delboy1978uk');
        $text->setId('user');
        $class = $text->getClass().' extra-class';
        $text->setClass($class);
        $form->addField($text);
        $text = new Text('firstname', 'delboy1978uk');
        $text->setId('firstname');
        $form->addField($text);
        $text = new Text('lastname', 'delboy1978uk');
        $text->setId('lastname');
        $form->addField($text);
        $fields = $form->getFields();
        $field = $fields->findByName('username');
        $this->assertInstanceOf('Del\Form\Field\Text', $field);
        $this->assertEquals('user', $field->getId());
        $field = $fields->findByName('firstname');
        $this->assertInstanceOf('Del\Form\Field\Text', $field);
        $this->assertEquals('firstname', $field->getId());
        $field = $fields->findByName('lastname');
        $this->assertInstanceOf('Del\Form\Field\Text', $field);
        $this->assertEquals('lastname', $field->getId());
    }

    public function testFindFieldInCollectionByNameReturnsNull()
    {
        $form = new Form('test');
        $fields = $form->getFields();
        $field = $fields->findByName('username');
        $this->assertNull($field);
    }

    public function testGetField()
    {
        $form = new Form('test');
        $text = new Text('username', 'delboy1978uk');
        $form->addField($text);
        $field = $form->getField('username');
        $this->assertInstanceOf('Del\Form\Field\Text', $field);
    }

    public function testPopulate()
    {
        $form = new Form('test');
        $text = new Text('username');
        $text->setId('user');
        $form->addField($text);
        $text = new Text('firstname');
        $text->setId('firstname');
        $form->addField($text);
        $text = new Text('lastname');
        $text->setId('lastname');
        $form->addField($text);

        $data = [
            'username' => 'delboy1978uk',
            'firstname' => 'Derek',
            'lastname' => 'McLean',
        ];

        $form->populate($data);

        $values = $form->getValues();
        $this->assertArrayHasKey('username', $values);
        $this->assertArrayHasKey('firstname', $values);
        $this->assertArrayHasKey('lastname', $values);
        $this->assertEquals('delboy1978uk', $values['username']);
        $this->assertEquals('Derek', $values['firstname']);
        $this->assertEquals('McLean', $values['lastname']);
    }

    public function testAddAndGetValidators()
    {
        $form = new Form('test');
        $text = new Text('username');

        $notEmpty = new ValidatorAdapterZf(new NotEmpty());

        $stringLength = new StringLength();
        $stringLength->setMax(10);
        $stringLength->setMin(2);
        $length = new ValidatorAdapterZf($stringLength);

        $text->setId('user');
        $text->addValidator($notEmpty);
        $text->addValidator($length);

        $form->addField($text);
        $validators = $text->getValidators();
        $this->assertInstanceOf('Del\Form\Collection\ValidatorCollection', $validators);
        $this->assertEquals(2, count($validators));
    }

    public function testValidateForm()
    {
        $form = new Form('test');
        $text = new Text('username');

        $stringLength = new StringLength();
        $stringLength->setMax(10);
        $stringLength->setMin(2);
        $length = new ValidatorAdapterZf($stringLength);

        $text->setId('user');
        $text->addValidator($length);
        $text->setRequired(true);

        $form->addField($text);
        $this->assertFalse($form->isValid());
        $form->getField('username')->setValue('Derek');
        $this->assertTrue($form->isValid());
    }



    public function testAddAndGetFilters()
    {
        $form = new Form('test');
        $text = new Text('username');

        $stripTags = new StripTags();
        $upperCase = new UpperCaseWords();
        $adapter = new FilterAdapterZf($stripTags);
        $adapter2 = new FilterAdapterZf($upperCase);

        $text->setId('user');
        $text->addFilter($adapter);
        $text->addFilter($adapter2);
        $text->setValue('delboy1978uk');

        $form->addField($text);
        $filters = $text->getFilters();
        $values = $form->getValues();
        $this->assertInstanceOf('Del\Form\Collection\FilterCollection', $filters);
        $this->assertEquals(4, count($filters));
        $this->assertArrayHasKey('username', $values);
        $this->assertEquals('Delboy1978uk', $values['username']);
    }

    public function testValidatorCollectionThrowsInvalidArgumentException()
    {
        $this->expectException('InvalidArgumentException');
        $collection = new ValidatorCollection();
        $collection->append(12345);
    }

    public function testFilterCollectionThrowsInvalidArgumentException()
    {
        $this->expectException('InvalidArgumentException');
        $collection = new FilterCollection();
        $collection->append(12345);
    }

    public function testOtherFieldTypes()
    {
        $form = new Form('testform');

        $validator = new ValidatorAdapterZf(new NotEmpty());
        $filter = new FilterAdapterZf(new StripTags());

        $name = new Text('Username');
        $email = new Text('Email');
        $radio = new Radio('Radio');
        $check = new CheckBox('Radio');
        $submit = new Submit('submit');

        $radio->setLabel('Choose your meal');
        $radio->setOptions([
            1 => 'Chicken',
            2 => 'Beef',
            3 => 'Pork',
        ]);

        $check->setLabel('Mailing List');
        $check->setOptions([
            'spam' => 'Spam my inbox',
        ]);

        $name->addValidator($validator);
        $name->addFilter($filter);
        $name->setPlaceholder('Enter Some Text');
        $this->assertEquals('Enter Some Text', $name->getPlaceholder());
        $name->setLabel('User Name');
        $email->setLabel('Email Address');

        $form->addField($name);
        $form->addField($email);
        $form->addField($radio);
        $form->addField($check);
        $form->addField($submit);

        $html = $form->render();
        $this->assertEquals('<form name="testform" method="post" id="testform"><div class="form-group"><label for="">User Name</label><input name="Username" type="text" class="form-control" placeholder="Enter Some Text"></div><div class="form-group"><label for="">Email Address</label><input name="Email" type="text" class="form-control"></div><div class="form-group"><label for="">Choose your meal</label><div class="radio"><label for=""><input type="radio" name="Radio" value="1">Chicken</label></div><div class="radio"><label for=""><input type="radio" name="Radio" value="2">Beef</label></div><div class="radio"><label for=""><input type="radio" name="Radio" value="3">Pork</label></div></div><div class="form-group"><label for="">Mailing List</label><div class="checkbox"><label for=""><input type="checkbox" name="Radio[]" value="spam">Spam my inbox</label></div></div><div class="form-group"><label for=""></label><input name="submit" value="submit" type="submit" class="btn btn-primary"></div></form>'."\n", $html);
    }


    public function testRenderWithErrors()
    {
        $form = new Form('testform');
        $text = new Text('text');
        $text->setRequired(true);
        $form->addField($text);
        $form->populate(['text' => null]);
        $html = $form->render();
        $this->assertEquals('<form name="testform" method="post" id="testform"><div class="has-error form-group"><label for=""><span class="text-danger">* </span></label><input name="text" type="text" class="form-control"><span class="help-block">Value is required and can\'t be empty<br></span></div></form>'."\n", $html);
    }


    public function testRenderWithCustomErrors()
    {
        $form = new Form('testform');
        $text = new Text('text');
        $text->setCustomErrorMessage('This can\'t be empty!')
             ->setRequired(true);
        $form->addField($text);
        $form->populate(['text' => null]);
        $html = $form->render();
        $this->assertEquals('<form name="testform" method="post" id="testform"><div class="has-error form-group"><label for=""><span class="text-danger">* </span></label><input name="text" type="text" class="form-control"><span class="help-block">This can\'t be empty!</span></div></form>'."\n", $html);
    }

    public function testGetAndSetRenderer()
    {
        $text = new Text('test');
        $text->setRenderer(new TextRender());
        $this->assertInstanceOf('Del\Form\Renderer\Field\TextRender', $text->getRenderer());
    }


    public function testGetAndSetEmailField()
    {
        $email = new EmailAddress('test');
        $email->setRequired(true);
        $email->setValue('delboy1978uk');
        $this->assertFalse($email->isValid());
        $email->setValue('delboy1978uk@gmail.com');
        $this->assertTrue($email->isValid());
    }


    public function testGetAndSetPasswordField()
    {
        $text = new Password('test', 'hello');
        $this->assertEquals('hello', $text->getValue());

    }



    public function testRenderFormTwice()
    {
        $form = new Form('checkboxtest');
        $form->render();
        $html = $form->render();
        $this->assertEquals('<form name="checkboxtest" method="post" id="checkboxtest"></form>'."\n", $html);
    }
}
