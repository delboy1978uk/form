<?php

declare(strict_types=1);

namespace Del\Test\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Field\Text;
use Del\Form\Form;
use Del\Form\Field\FileUpload;
use Del\Form\Renderer\Field\FileUploadRender;
use Del\Form\Renderer\Field\TextRender;
use function realpath;

class FileUploadTest extends Unit
{
    public function testRendererThrowsException()
    {
        $form = new Form('test');
        $text = new Text('oops');
        $text->setRenderer(new FileUploadRender());
        $form->addField($text);
        $this->expectException('InvalidArgumentException');
        $form->render();
    }


    public function testFileUploadField()
    {
        $form = new Form('photo-upload');
        $pic = new FileUpload('photo');
        $pic->setRequired(true);
        $form->addField($pic);
        $this->assertFalse($form->isValid());
        $html = $form->render();
        $this->assertEquals('<form name="photo-upload" method="post" id="photo-upload"><div class="form-group" id="photo-form-group"><label for=""><span class="text-danger">* </span></label><div class="input-group"><span class="input-group-btn"><span class="btn btn-primary btn-file"><input name="photo" type="file"></span></span><input type="text" class="form-control" readonly><style type="text/css">    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
    input[readonly] {
        background-color: white !important;
        cursor: text !important;
    }</style><script type="text/javascript">' . "    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
    });</script></div></div></form>\n", $html);
    }


    public function testFileUploadWithErrorBlock()
    {
        $form = new Form('photo-upload');
        $form->setDisplayErrors(true);
        $pic = new FileUpload('photo');
        $pic->setRequired(true);
        $pic->setRenderer(new TextRender()); // for plain output without bootstrap and js etc
        $form->addField($pic);
        $this->assertFalse($form->isValid());
        $html = $form->render();
        $this->assertEquals('<form name="photo-upload" method="post" id="photo-upload"><div class="has-error form-group" id="photo-form-group"><label for=""><span class="text-danger">* </span></label><input name="photo" type="file"><span class="text-danger">Value is required and can\'t be empty<br></span></div></form>' . "\n", $html);
    }

    public function testMovingUploadsDoesntWorkWithFakeUploadArray()
    {
        $image = realpath(__DIR__ . '/../../../_data/fol.gif');
        $_POST = [
            'photo' => $image,
            'submit' => 'submit',
        ];
        $_FILES = [
            'photo' => [
                'name' => 'fol.gif',
                'type' => 'image/gif',
                'tmp_name' => $image,
                'error' => 0,
                'size' => 10363,
            ],
        ];
        $form = new Form('photo-upload');
        $form->setEncType(Form::ENC_TYPE_MULTIPART_FORM_DATA);
        $form->setDisplayErrors(true);
        $pic = new FileUpload('photo');
        $pic->setRequired(true);
        $pic->setRenderer(new TextRender());
        $dir = 'tests/_output';
        $pic->setUploadDirectory($dir);
        $form->addField($pic);
        $this->assertTrue($form->isValid());
        $path = (\getcwd() . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $pic->getValue());
        $fileExists = \file_exists($path);
        $this->assertFalse($fileExists);
    }

    public function testExceptionWhenDestinationNotSet()
    {
        $image = realpath(__DIR__ . '/../../../_data/fol.gif');
        $_POST = [
            'photo' => $image,
            'submit' => 'submit',
        ];
        $_FILES = [
            'photo' => [
                'name' => 'fol.gif',
                'type' => 'image/gif',
                'tmp_name' => $image,
                'error' => 0,
                'size' => 10363,
            ],
        ];
        $form = new Form('photo-upload');
        $pic = new FileUpload('photo');
        $pic->setRequired(true);
        $pic->setRenderer(new TextRender());
        $form->addField($pic);
        $this->expectException('LogicException');
        $form->isValid();
    }

    public function testSetUploadDirectoryThrowsException()
    {
        $image = realpath(__DIR__ . '/../../../_data/fol.gif');
        $pic = new FileUpload('photo');
        $this->expectException('InvalidArgumentException');
        $pic->setUploadDirectory($image);
    }
}
