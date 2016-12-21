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

class CheckBox extends FieldAbstract implements ArrayValueInterface
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
        $this->setValue([]);
        $this->renderInline = false;
        $this->setRenderer(new CheckboxRender());
    }

    /**
     * @param $key
     * @return $this
     */
    public function checkValue($key)
    {
        $values = $this->getValue();
        $values[$key] = true;
        $this->setValue($values);
        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function uncheckValue($key)
    {
        $values = $this->getValue();
        if (isset($values[$key])) {
            unset($values[$key]);
        }
        $this->setValue($values);
        return $this;
    }
}