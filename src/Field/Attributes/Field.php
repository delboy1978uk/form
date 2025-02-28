<?php

declare(strict_types=1);

namespace Del\Form\Field\Attributes;

use Attribute;

#[Attribute]
class Field
{
    public function __construct(
        public readonly string $rules
    ) {}
}
