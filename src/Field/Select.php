<?php
/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 00:57
 */

namespace Del\Form\Field;

use Del\Form\Renderer\Field\SelectRender;
use Del\Form\Traits\HasOptionsTrait;

class Select extends FieldAbstract
{
    use HasOptionsTrait;

    /**
     * @return string
     */
    public function getTag()
    {
        return 'select';
    }

    public function init()
    {
        $this->setAttribute('type', 'text');
        $this->setAttribute('class', 'form-control');
        $this->setRenderer(new SelectRender());
    }
}