<?php
/**
 * User: delboy1978uk
 * Date: 27/11/2016
 * Time: 13:41
 */

namespace Del\Form\Collection;

use Del\Common\Collection\AbstractCollection;
use Del\Common\Collection\CollectionInterface;
use Del\Form\Filter\FilterInterface;

class FilterCollection extends AbstractCollection implements CollectionInterface
{
    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function append(FilterInterface $filter)
    {
        parent::append($filter);
        return $this;
    }

    /**
     * @return FilterInterface
     */
    public function current()
    {
        return parent::current();
    }
}