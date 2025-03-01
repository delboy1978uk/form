<?php

namespace Del\Test\Form\Field\Transformer;

use Codeception\Test\Unit;
use DateTime;
use Del\Form\Field\Transformer\DateTimeTransformer;
use Del\Form\Form;
use Del\Form\Field\Text;

class DateTimeTransformerTest extends Unit
{
    public function testPlainText()
    {
        $format = 'Y-m-d';
        $form = new Form('required-text-form');
        $date = new Text('date');
        $date->setTransformer(new DateTimeTransformer($format));
        $form->addField($date);
        $form->populate(['date' => '2014-09-18']);
        $data = $form->getValues(true);
        $value = $data['date'];

        $this->assertInstanceOf(DateTime::class, $value);
        $this->assertEquals('2014-09-18', $value->format($format));

        $data = $form->getValues();
        $value = $data['date'];

        $this->assertEquals('2014-09-18', $value);
    }

    public function testDateTime()
    {
        $format = 'Y-m-d';
        $form = new Form('required-text-form');
        $date = new Text('date');
        $date->setTransformer(new DateTimeTransformer($format));
        $form->addField($date);
        $form->populate(['date' => new DateTime('2014-09-18')]);
        $data = $form->getValues(true);
        $value = $data['date'];

        $this->assertInstanceOf(DateTime::class, $value);
        $this->assertEquals('2014-09-18', $value->format($format));

        $data = $form->getValues();
        $value = $data['date'];

        $this->assertEquals('2014-09-18', $value);
    }
}
