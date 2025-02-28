<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Renderer\Field\CheckboxRender;
use Del\Form\Traits\CanRenderInlineTrait;
use Del\Form\Traits\HasOptionsTrait;

class CheckBox extends FieldAbstract implements ArrayValueInterface
{

    use CanRenderInlineTrait;
    use HasOptionsTrait;

    /*
     * We end up ignoring this during rendering Checkboxes, see the renderer for info
     */
    public function getTag(): string
    {
        return 'div';
    }

    public function init(): void
    {
        $this->setValue([]);
        $this->setRenderInline(false);
        $this->setRenderer(new CheckboxRender());
    }

    /**
     * @param $key
     */
    public function checkValue($key): void
    {
        $values = $this->getValue();
        $values[$key] = true;
        $this->setValue($values);
    }

    /**
     * @param $key
     */
    public function uncheckValue($key): void
    {
        $value = $this->getValue();

        if (is_array($value) && in_array($key, $value, true)) {
            $index = array_search($key, $value, true);
            unset($value[$index]);
            $this->setValue($value);
        } else {
            $this->setValue(null);
        }
    }
}
