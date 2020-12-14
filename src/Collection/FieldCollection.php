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
     * @param $name
     * @return FieldInterface|null
     */
    public function removeByName($name): bool
    {
        $this->rewind();

        while ($this->valid()) {
            $field = $this->current();

            if ($field->getName() == $name) {
                $key = $this->key();
                $this->offsetUnset($key);
                $this->rewind();

                $result = true;
            }
        }

        $this->rewind();

        return false;
    }

    /**
     * @return FieldInterface
     */
    public function current(): FieldInterface
    {
        return parent::current();
    }
}