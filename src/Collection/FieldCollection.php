<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 12:18
 */

namespace Del\Form\Collection;

use Del\Form\Field\FieldInterface;

class FieldCollection extends AbstractCollection implements CollectionInterface
{
    /**
     * @param $name
     * @return FieldInterface|null
     */
    public function findByName($name)
    {
        $this->rewind();
        while ($this->valid()) {
            /** @var FieldInterface $field */
            $field = $this->current();
            if ($field->getName() == $name) {
                return $field;
            }
            $this->next();
        }
        $this->rewind();
        return null;
    }

    /**
     * @return FieldInterface
     */
    public function current()
    {
        return parent::current();
    }
}