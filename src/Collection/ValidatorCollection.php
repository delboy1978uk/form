<?php

declare(strict_types=1);

namespace Del\Form\Collection;

use Del\Form\Validator\ValidatorInterface;
use InvalidArgumentException;

class ValidatorCollection extends AbstractCollection implements CollectionInterface
{
    public function append(mixed $validator): void
    {
        if (!$validator instanceof ValidatorInterface) {
            throw new InvalidArgumentException('You can only append a Del\Form\Validator\ValidatorInterface.');
        }
        parent::append($validator);
    }

    public function current(): mixed
    {
        return parent::current();
    }
}
