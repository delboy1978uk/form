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
     * We end up ignoring this during rendering Radios, see the renderer for info
     *
     * @return string
     */
    public function getTag()
    {
        return 'div';
    }

    public function init()
    {
        $this->renderInline = false;
        $this->setRenderer(new RadioRender());
    }
}