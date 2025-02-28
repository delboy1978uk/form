<?php

declare(strict_types=1);

namespace Del\Form\Traits;

trait CanRenderInlineTrait
{
    private bool $renderInline = false;

    public function isRenderInline(): bool
    {
        return $this->renderInline;
    }

    public function setRenderInline($renderInline): void
    {
        $this->renderInline = $renderInline;
    }
}
