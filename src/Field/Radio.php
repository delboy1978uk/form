<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field;

use Del\Form\Renderer\Field\RadioRender;
use Del\Form\Traits\CanRenderInlineTrait;
use Del\Form\Traits\HasOptionsTrait;

class Radio extends FieldAbstract
{
    use CanRenderInlineTrait;
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
        $this->renderInline = false;
        $this->setAttribute('class', 'radio');
        $this->setRenderer(new RadioRender());
    }
}