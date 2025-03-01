<?php

namespace Del\Test\Form;

use Codeception\Test\Unit;
use Del\Form\Field\Radio;
use Del\Form\Field\Submit;
use Del\Form\Field\Text;
use Del\Form\Form;
use Del\Form\Renderer\HorizontalFormRenderer;

class DynamicFormTest extends Unit
{
    public function testGetDynamicFormFieldThrowsException()
    {
        $text = new Text('fail');
        $this->expectException('Exception');
        $text->getDynamicForms();
    }


    public function testDynamicFormBeforePopulation()
    {
        $form = $this->getForm();
        $html = $form->render();
        $this->assertStringContainsString('<form class="form-horizontal" name="dynamic" method="post" id="dynamic"><div class=" form-group row"><label for="" class="col-sm-2 col-md-3 control-label"><span class="text-danger">* </span>Name</label><div class="col"><input name="name" type="text" class="form-control" placeholder="Type your name.."></div></div><div class=" form-group row"><label for="" class="col-sm-2 col-md-3 control-label"><span class="text-danger">* </span>Email Address</label><div class="col"><input name="email" type="email" class="form-control" placeholder="Enter an email address.."></div></div><div class=" form-group row"><label for="" class="col-sm-2 col-md-3 control-label"><span class="text-danger">* </span>Please choose..</label><div class="col"><div class="radio"><div class="form-check-inline"><input class="form-check-input" type="radio" name="choice" value="1"><label for="1" class="form-check-label">Food</label></div><div class="form-check-inline"><input class="form-check-input" type="radio" name="choice" value="2"><label for="2" class="form-check-label">Drink</label></div></div></div></div><div data-dynamic-form="choice" data-dynamic-form-trigger-value="1" class="dynamic-form-block triggerchoice" id="choice1" style="display: none;"><div class=" form-group row"><label for="" class="col-sm-2 col-md-3 control-label"><span class="text-danger">* </span>Choose your food.</label><div class="col"><div class="radio"><div class="form-check"><input class="form-check-input" type="radio" name="foodchoice" value="1"><label for="1" class="form-check-label">Cheeseburger</label></div><div class="form-check"><input class="form-check-input" type="radio" name="foodchoice" value="2"><label for="2" class="form-check-label">Pizza</label></div><div class="form-check"><input class="form-check-input" type="radio" name="foodchoice" value="3"><label for="3" class="form-check-label">Steak</label></div></div></div></div></div><div data-dynamic-form="choice" data-dynamic-form-trigger-value="2" class="dynamic-form-block triggerchoice" id="choice2" style="display: none;"><div class=" form-group row"><label for="" class="col-sm-2 col-md-3 control-label"><span class="text-danger">* </span>Choose your drink.</label><div class="col"><div class="radio"><div class="form-check"><input class="form-check-input" type="radio" name="drinkchoice" value="1"><label for="1" class="form-check-label">Beer</label></div><div class="form-check"><input class="form-check-input" type="radio" name="drinkchoice" value="2"><label for="2" class="form-check-label">Vodka</label></div><div class="form-check"><input class="form-check-input" type="radio" name="drinkchoice" value="3"><label for="3" class="form-check-label">Whisky</label></div></div></div></div><div class=" form-group row"><label for="" class="col-sm-2 col-md-3 control-label">whatever</label><div class="col"><input name="moretext" type="text" class="form-control" placeholder="Another text field to fill in"></div></div></div><div class=" form-group row"><div class="col"><input name="submit" value="submit" type="submit" class="btn btn-primary"></div></div></form>' . "\n", $html);
        $this->assertStringContainsString("<script type=\"text/javascript\">
                $(document).ready(function(){
                    $('.dynamic-form-block').each(function(){
                        var Id = $(this).prop('id');
                        var parentField = $(this).attr('data-dynamic-form');
                        var parentValue = $(this).attr('data-dynamic-form-trigger-value');
            
                        $('input[name=\"'+parentField+'\"]').change(function(){
                            var val = $(this).val();
                            if (val == parentValue) {
                                $('.trigger'+parentField).each(function(){
                                    $(this).attr('style', 'display: none;');
                                });
                                $('#'+Id).attr('style', 'display: block;');
                            }
                        });
                    });
                });
            </script>", $html);
    }


    public function testFormValidatesWithDynamicForms()
    {
        $form = $this->getForm();
        $data = [
            'name' => 'Derek',
            'email' => 'delboy1978uk@gmail.com',
            'choice' => '1',
        ];
        $form->populate($data);
        $this->assertFalse($form->isValid());
        $data['foodchoice'] = '1';
        $form->populate($data);
        $this->assertTrue($form->isValid());

    }


    public function testGetValues()
    {
        $form = $this->getForm();
        $data = [
            'name' => 'Derek',
            'email' => 'delboy1978uk@gmail.com',
            'choice' => '1',
            'foodchoice' => '1',
        ];
        $form->populate($data);
        $data = $form->getValues();
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('choice', $data);
        $this->assertArrayHasKey('foodchoice', $data);
        $this->assertArrayHasKey('submit', $data);
        $this->assertEquals('Derek', $data['name']);
        $this->assertEquals('delboy1978uk@gmail.com', $data['email']);
        $this->assertEquals('1', $data['choice']);
        $this->assertEquals('1', $data['foodchoice']);

    }


    /**
     * @return Form
     */
    private function getForm()
    {
        $form = new Form('dynamic');
        $form->setFormRenderer(new HorizontalFormRenderer());

        $text = new Text('name');
        $text->setLabel('Name');
        $text->setPlaceholder('Type your name..');
        $text->setRequired(true);

        $email = new Text\EmailAddress('email');
        $email->setLabel('Email Address');
        $email->setRequired(true);
        $email->setCustomErrorMessage('Please enter a valid email address.');

        $radio = new Radio('choice');
        $radio->setLabel('Please choose..');
        $radio->setRenderInline(true);
        $radio->setRequired(true);
        $radio->setOptions([
            1 => 'Food',
            2 => 'Drink',
        ]);

        $foodForm = new Form('food');
        $foodRadio = new Radio('foodchoice');
        $foodRadio->setLabel('Choose your food.');
        $foodRadio->setRequired(true);
        $foodRadio->setOptions([
            1 => 'Cheeseburger',
            2 => 'Pizza',
            3 => 'Steak',
        ]);
        $foodForm->addField($foodRadio);
        $radio->addDynamicForm($foodForm, 1);

        $drinkForm = new Form('drink');
        $drinkRadio = new Radio('drinkchoice');
        $drinkRadio->setRequired(true);
        $drinkRadio->setLabel('Choose your drink.');
        $drinkRadio->setOptions([
            1 => 'Beer',
            2 => 'Vodka',
            3 => 'Whisky',
        ]);
        $moreText = new Text('moretext');
        $moreText->setLabel('whatever');
        $moreText->setPlaceholder('Another text field to fill in');
        $drinkForm->addField($drinkRadio);
        $drinkForm->addField($moreText);
        $radio->addDynamicForm($drinkForm, 2);

        $submit = new Submit('submit');

        $form->addField($text);
        $form->addField($email);
        $form->addField($radio);
        $form->addField($submit);

        return $form;
    }
}
