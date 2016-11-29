<?php

namespace Del\Form\Filter\Adapter;

use Del\Form\Filter\FilterInterface;
use Zend\Filter\FilterInterface as ZendFilterInterface;

class FilterAdapterZf implements FilterInterface
{
    private $filter;

    /**
     * FilterAdapterZf constructor.
     */
    public function __construct(ZendFilterInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        return $this->filter->filter($value);
    }
}