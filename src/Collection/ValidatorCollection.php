<?php
/**
 * User: delboy1978uk
 * Date: 27/11/2016
 * Time: 13:41
 */

namespace Del\Form\Collection;

use Del\Common\Collection\AbstractCollection;
use Del\Common\Collection\CollectionInterface;
use Del\Form\Validator\ValidatorInterface;

class ValidatorCollection extends AbstractCollection implements CollectionInterface
{
    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function append(ValidatorInterface $validator)
    {
        parent::append($validator);
        return $this;
    }

    /**
     * @return ValidatorInterface
     */
    public function current()
    {
        return parent::current();
    }
}