<?php

namespace Del\Test\Form\Validator;

use Codeception\Test\Unit;
use Del\Form\Validator\MaxLength;
use Del\Form\Validator\MinLength;

class LenghValidatorTest extends Unit
{
    public function testMaxLengthyValidation()
    {
        $validator = new MaxLength(5);
        $this->assertTrue($validator->isValid('works'));
        $this->assertFalse($validator->isValid('broken'));
        $this->assertEquals('Exceeded maximum length of 5', $validator->getMessages()[0]);
    }

    public function testMinLengthyValidation()
    {
        $validator = new MinLength(5);
        $this->assertTrue($validator->isValid('passes'));
        $this->assertFalse($validator->isValid('fail'));
        $this->assertEquals('Minimum length must be 5', $validator->getMessages()[0]);
    }
}
