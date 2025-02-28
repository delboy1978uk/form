<?php

declare(strict_types=1);

namespace Del\Form\Filter;

use Exception;

interface FilterInterface
{
    /**
     * Returns the result of filtering $value
     *
     * @throws Exception If filtering $value is impossible
     */
    public function filter(mixed $value): mixed;
}
