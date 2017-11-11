<?php
/**
 * User: delboy1978uk
 * Date: 27/11/2016
 * Time: 13:41
 */

namespace Del\Form\Collection;

use Del\Form\Filter\FilterInterface;
use InvalidArgumentException;

class FilterCollection extends AbstractCollection implements CollectionInterface
{
    /**
     * @param FilterInterface $filter Pass in a filter
     * @return $this
     */
    public function append($filter)
    {
        if (!$filter instanceof FilterInterface) {
            throw new InvalidArgumentException('You can only append a Del\Form\Filter\FilterInterface.');
        }
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