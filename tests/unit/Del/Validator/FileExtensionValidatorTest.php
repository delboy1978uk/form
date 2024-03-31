<?php

namespace DelTesting\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Validator\FileExtensionValidator;

class FileExtensionValidatorTest extends Unit
{
    public function testFileExtensionValidation()
    {
        $validator = new FileExtensionValidator(['png']);
        $this->assertTrue($validator->isValid('photo.png'));
        $this->assertTrue($validator->isValid('photo.PNG'));
        $this->assertFalse($validator->isValid('photo.jpg'));
        $this->assertEquals('The file must be a png file.', $validator->getMessages()[0]);

        $validator = new FileExtensionValidator(['png', 'jpg']);
        $this->assertTrue($validator->isValid('photo.png'));
        $this->assertTrue($validator->isValid('photo.PNG'));
        $this->assertTrue($validator->isValid('photo.jpg'));
        $this->assertFalse($validator->isValid('photo.xml'));
        $this->assertEquals('The file must be either a png or jpg file.', $validator->getMessages()[0]);
    }
}
