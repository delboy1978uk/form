<?php
/**
 * User: delboy1978uk
 * Date: 27/11/2016
 * Time: 13:50
 */

namespace Del\Form\Filter;

use Exception;

interface FilterInterface
{
    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception If filtering $value is impossible
     * @return mixed
     */
    public function filter($value);
}