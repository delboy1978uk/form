<?php

namespace DelTesting\Form\Field;

use Codeception\TestCase\Test;
use Del\Form\Field\Text;
use Del\Form\Form;
use Del\Form\Field\FileUpload;
use Del\Form\Renderer\Field\FileUploadRender;
use Del\Form\Renderer\Field\TextRender;

/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 02:27
 */
class FileUploadTest extends Test
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
        $this->assertEquals('<form name="photo-upload" method="post" id="photo-upload"><div class="form-group"><label for=""><span class="text-danger">* </span></label><div class="input-group"><span class="input-group-btn"><span class="btn btn-primary btn-file"><input name="photo" type="file"></span></span><input type="text" class="form-control" readonly><style type="text/css">    .btn-file {
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
    }</style><script type="text/javascript">'."    $(document).on('change', '.btn-file :file', function() {
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
        $this->assertEquals('<form name="photo-upload" method="post" id="photo-upload"><div class="has-error form-group"><label for=""><span class="text-danger">* </span></label><input name="photo" type="file"><span class="help-block">Value is required and can\'t be empty<br></span></div></form>'."\n", $html);
    }
}