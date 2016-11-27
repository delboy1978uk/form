<?php

namespace DelTesting\Form;

use Codeception\TestCase\Test;
use Del\Form\Field\Text;

class FormTest extends Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    public function testGetSetId()
    {
        $form = new TestForm('test');
        $form->setId('testId');
        $this->assertEquals('testId', $form->getId());
    }

    public function testGetSetClass()
    {
        $form = new TestForm('test');
        $form->setClass('some-class');
        $this->assertEquals('some-class', $form->getClass());
    }

    public function testGetSetMethod()
    {
        $form = new TestForm('test');
        $form->setMethod(TestForm::METHOD_GET);
        $this->assertEquals(TestForm::METHOD_GET, $form->getMethod());
    }

    public function testGetSetAction()
    {
        $form = new TestForm('test');
        $form->setAction('/some/url');
        $this->assertEquals('/some/url', $form->getAction());
    }

    public function testGetSetEncType()
    {
        $form = new TestForm('test');
        $form->setEncType(TestForm::ENC_TYPE_MULTIPART_FORM_DATA);
        $this->assertEquals(TestForm::ENC_TYPE_MULTIPART_FORM_DATA, $form->getEncType());
    }

    /**
     * Check tests are working
     */
    public function testRender()
    {
        $form = new TestForm('test');
        $form->setAction('/here');
        $form->setId('testId');
        $form->setClass('random-class');
        $form->setEncType(TestForm::ENC_TYPE_MULTIPART_FORM_DATA);
        $form->setMethod(TestForm::METHOD_GET);
        $html = $form->render();

        $this->assertEquals('/here', $form->getAction());
        $this->assertEquals('testId', $form->getId());
        $this->assertEquals('random-class', $form->getClass());
        $this->assertEquals('<form name="test" action="/here" id="testId" class="random-class" enctype="multipart/form-data" method="get"></form>'."\n", $html);
    }

    public function testTextField()
    {
        $form = new TestForm('test');
        $text = new Text('username', 'delboy1978uk');
        $text->setId('user');
        $form->addField($text);
        $values = $form->getValues();
        $html = $form->render();
        $this->assertEquals('<form name="test" id="test" method="post" action=""><input type="text" name="username" id="user" value="delboy1978uk" class="form-control"></form>'."\n", $html);
        $this->assertArrayHasKey('username', $values);
        $this->assertEquals('delboy1978uk', $values['username']);
    }

    public function testFindFieldInCollectionByName()
    {
        $form = new TestForm('test');
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
        $this->assertEquals('text', $field->getTagType());

        $form = new TestForm('test');
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
        $form = new TestForm('test');
        $fields = $form->getFields();
        $field = $fields->findByName('username');
        $this->assertNull($field);
    }

    public function testGetField()
    {
        $form = new TestForm('test');
        $text = new Text('username', 'delboy1978uk');
        $form->addField($text);
        $field = $form->getField('username');
        $this->assertInstanceOf('Del\Form\Field\Text', $field);
    }

    public function testPopulate()
    {
        $form = new TestForm('test');
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
}
