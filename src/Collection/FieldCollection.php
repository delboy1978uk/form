<?php

declare(strict_types=1);

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
     * @return bool
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

                return true;
            }
            $this->next();
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
