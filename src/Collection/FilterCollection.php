<?php

declare(strict_types=1);

namespace Del\Form\Collection;

use Del\Form\Filter\FilterInterface;
use InvalidArgumentException;

class FilterCollection extends AbstractCollection implements CollectionInterface
{
    /**
     * @param FilterInterface $filter Pass in a filter
     * @return $this
     */
    public function append(mixed $filter): void
    {
        if (!$filter instanceof FilterInterface) {
            throw new InvalidArgumentException('You can only append a Del\Form\Filter\FilterInterface.');
        }
        parent::append($filter);
    }

    /**
     * @return FilterInterface
     */
    public function current(): mixed
    {
        return parent::current();
    }
}
