<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Validator\MimeTypeValidator;

class MimeTypeValidatorTest extends Test
{
    public function testMimeValidation()
    {
        $_FILES = [
            'image' => [
                'tmp_name' => 'tests/_data/fol.gif'
            ],
        ];
        $validator = new MimeTypeValidator(['image/gif'], 'image');
        $this->assertTrue($validator->isValid('fol.gif'));
        $_FILES = [
            'image' => [
                'tmp_name' => 'tests/_data/form.png'
            ],
        ];
        $validator = new MimeTypeValidator(['image/gif'], 'image');
        $this->assertFalse($validator->isValid('form.png'));
        $this->assertEquals('Invalid mime type.', $validator->getMessages()[0]);
    }
}