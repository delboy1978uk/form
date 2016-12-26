<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 23:50
 */

namespace Del\Form\Renderer\Error;

use DOMDocument;
use Del\Form\Field\FieldInterface;
use Del\Form\Traits\HasDomTrait;

abstract class AbstractErrorRender implements ErrorRendererInterface
{
    use HasDomTrait;

    /**
     * AbstractErrorRender constructor.
     * @param DOMDocument $dom
     */
    public function __construct(DOMDocument $dom)
    {
        $this->setDom($dom);
    }

    /**
     * @param FieldInterface $field
     * @return bool
     */
    public function shouldRender(FieldInterface $field)
    {
        return !$field->isValid() && ($field->isRequired() || !empty($field->getValue()));
    }
}