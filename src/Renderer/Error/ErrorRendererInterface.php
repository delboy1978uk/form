<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 23:37
 */

namespace Del\Form\Renderer\Error;

use Del\Form\Field\FieldInterface;


interface ErrorRendererInterface
{
    /**
     * @param FieldInterface $field
     * @return mixed
     */
    public function render(FieldInterface $field);

    /**
     * @param FieldInterface $field
     * @return bool
     */
    public function shouldRender(FieldInterface $field);
}