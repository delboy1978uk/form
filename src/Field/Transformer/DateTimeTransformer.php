<?php

declare(strict_types=1);

namespace Del\Form\Field\Transformer;

use DateTime;
use DateTimeInterface;
use Del\Form\Field\TransformerInterface;

class DateTimeTransformer implements TransformerInterface
{
    public function __construct(
        private string $dateFormat
    ) {}

    public function input(mixed $data): string
    {
        if ($data instanceof DateTimeInterface) {
            return $data->format($this->dateFormat);
        }

        return $data;
    }

    public function output(string $value): DateTimeInterface
    {
        return DateTime::createFromFormat($this->dateFormat, $value);
    }
}
