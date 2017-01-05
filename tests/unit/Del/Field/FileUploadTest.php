<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Form;
use Del\Form\Field\FileUpload;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class FileUploadTest extends Test
{
    public function testFileUploadField()
    {
        $form = new Form('photo-upload');
        $pic = new FileUpload('photo');
        $pic->setRequired(true);
        $form->addField($pic);
        $this->assertFalse($form->isValid());
        $html = $form->render();
        $this->assertEquals('', $html);
    }
}