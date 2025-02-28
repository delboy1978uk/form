<?php

declare(strict_types=1);

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

    public function filter(mixed $value): mixed
    {
        return $this->filter->filter($value);
    }
}
