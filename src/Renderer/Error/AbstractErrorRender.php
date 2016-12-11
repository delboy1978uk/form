<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 23:50
 */

namespace Del\Form\Renderer\Error;

use DOMDocument;
use Del\Form\Field\FieldInterface;

abstract class AbstractErrorRender implements ErrorRendererInterface
{
    /** @var DOMDocument */
    protected $dom;

    /**
     * AbstractErrorRender constructor.
     * @param DOMDocument $dom
     */
    public function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
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