<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Renderer\Field\RadioRender;
use Del\Form\Traits\CanRenderInlineTrait;
use Del\Form\Traits\HasOptionsTrait;

class Radio extends FieldAbstract implements ArrayValueInterface
{
    use CanRenderInlineTrait;
    use HasOptionsTrait;

    /*
     * We end up ignoring this during rendering Radios, see the renderer for info
     */
    public function getTag(): string
    {
        return 'div';
    }

    public function init(): void
    {
        $this->setRenderInline(false);
        $this->setRenderer(new RadioRender());
    }
}
