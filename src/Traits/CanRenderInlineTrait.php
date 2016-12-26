<?php
/**
 * Created by PhpStorm.
 * User: DM0C60544
 * Date: 15/12/2016
 * Time: 12:32 PM
 */

namespace Del\Form\Traits;


trait CanRenderInlineTrait
{
    /** @var bool $renderInline */
    private $renderInline;

    /**
     * @return boolean
     */
    public function isRenderInline()
    {
        return $this->renderInline;
    }

    /**
     * @param boolean $renderInline
     * @return $this
     */
    public function setRenderInline($renderInline)
    {
        $this->renderInline = $renderInline;
        return $this;
    }
}