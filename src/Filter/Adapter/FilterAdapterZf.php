<?php

namespace Del\Form\Filter\Adapter;

use Del\Form\Filter\FilterInterface;
use Laminas\Filter\FilterInterface as LaminasFilterInterface;

class FilterAdapterZf implements FilterInterface
{
    private $filter;

    /**
     * FilterAdapterZf constructor.
     */
    public function __construct(LaminasFilterInterface $filter)
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