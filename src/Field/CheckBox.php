<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field;

use Del\Form\Renderer\Field\CheckboxRender;
use Del\Form\Traits\CanRenderInlineTrait;
use Del\Form\Traits\HasOptionsTrait;

class CheckBox extends FieldAbstract
{

    use CanRenderInlineTrait;
    use HasOptionsTrait;

    /**
     * We end up ignoring this during rendering Checkboxes, see the renderer for info
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
        $this->setRenderer(new CheckboxRender());
    }


}