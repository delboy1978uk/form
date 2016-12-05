<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field;

use Del\Form\Renderer\Field\RadioRender;

class Radio extends FieldAbstract
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
        $this->setAttribute('type', 'radio');
        $this->setAttribute('class', 'form-control');
        $this->setRenderer(new RadioRender());
    }
}