<?php

declare(strict_types=1);

namespace Del\Form\Field\Transformer;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Del\Form\Field\TransformerInterface;

class DateTimeLocalTransformer implements TransformerInterface
{
    public function __construct(
        private string $dateFormat
    ) {}

    public function input(mixed $data): string
    {
        if ($data instanceof DateTimeInterface) {
            return $data->format($this->dateFormat);
        }

        return $data !== null ? $data : '';
    }

    public function output(string $value): ?DateTimeInterface
    {
        /** @@todo local timezone? */
        $date = new DateTime($value, new DateTimeZone('UTC'));

        return $date ?: null;
    }
}
