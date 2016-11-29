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
use InvalidArgumentException;

class ValidatorCollection extends AbstractCollection implements CollectionInterface
{
    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function append($validator)
    {
        if(!$validator instanceof ValidatorInterface) {
            throw new InvalidArgumentException('You can only append a Del\Form\Validator\ValidatorInterface.');
        }
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