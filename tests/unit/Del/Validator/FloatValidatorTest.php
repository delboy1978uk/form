<?php

namespace DelTesting\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Validator\FloatValidator;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class FloatValidatorTest extends Unit
{
    public function testFloatValidation()
    {
        $validator = new FloatValidator();
        $this->assertTrue($validator->isValid(12.34));
        $this->assertTrue($validator->isValid(56));
        $this->assertTrue($validator->isValid('10.34'));
        $this->assertTrue($validator->isValid('19'));
        $this->assertFalse($validator->isValid('hello'));
        $this->assertEquals('Value is not a float.', $validator->getMessages()[0]);
    }
}
