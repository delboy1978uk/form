<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field;

use Del\Form\Renderer\Field\RadioRender;
use Del\Form\Traits\HasOptionsTrait;

class Radio extends FieldAbstract
{
    use HasOptionsTrait;
    /**
     * @return string
     */
    public function getTag()
    {
        return 'div';
    }

    public function init()
    {
        $this->setAttribute('class', 'radio');
        $this->setRenderer(new RadioRender());
    }
}