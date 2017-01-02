<?php
/**
 * User: delboy1978uk
 * Date: 01/01/2017
 * Time: 19:58
 */

namespace Del\Form\Field;

use Del\Form\Renderer\Field\FileUploadRender;

class FileUpload extends FieldAbstract implements FieldInterface
{
    /**
     * @return string
     */
    public function getTag()
    {
        return 'input';
    }

    public function init()
    {
        $this->setAttribute('type', 'file');
        $this->setRenderer(new FileUploadRender());
    }
}