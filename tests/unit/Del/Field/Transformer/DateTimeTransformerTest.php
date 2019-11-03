<?php

namespace DelTesting\Form\Field\Transformer;

use Codeception\TestCase\Test;
use DateTime;
use Del\Form\Field\Transformer\DateTimeTransformer;
use Del\Form\Form;
use Del\Form\Field\Text;

class DateTimeTransformerTest extends Test
{
    public function testPlainText()
    {
        $format = 'Y-m-d';
        $form = new Form('required-text-form');
        $date = new Text('date');
        $date->setTransformer(new DateTimeTransformer($format));
        $form->addField($date);
        $form->populate(['date' => '2014-09-18']);
        $data = $form->getValues();
        $value = $data['date'];

        $this->assertInstanceOf(DateTime::class, $value);
        $this->assertEquals('2014-09-18', $value->format($format));
    }

    public function testDateTime()
    {
        $format = 'Y-m-d';
        $form = new Form('required-text-form');
        $date = new Text('date');
        $date->setTransformer(new DateTimeTransformer($format));
        $form->addField($date);
        $form->populate(['date' => new DateTime('2014-09-18')]);
        $data = $form->getValues();
        $value = $data['date'];

        $this->assertInstanceOf(DateTime::class, $value);
        $this->assertEquals('2014-09-18', $value->format($format));
    }
}
