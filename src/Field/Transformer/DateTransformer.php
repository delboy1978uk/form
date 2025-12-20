<?php

declare(strict_types=1);

namespace Del\Form\Field\Transformer;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Del\Form\Field\TransformerInterface;

class DateTransformer implements TransformerInterface
{
    public function __construct(
        private string $dateFormat
    ) {}

    public function input(mixed $data): string
    {
        if ($data instanceof DateTimeInterface) {
            return $data->format('Y-m-d');
        }

        return $data !== null ? $data : '';
    }

    public function output(string $value): ?DateTimeInterface
    {
        $date =  DateTime::createFromFormat($this->dateFormat, $value, new DateTimeZone('UTC'));

        if (!$date) {
            $date = new DateTime($value, new DateTimeZone('UTC'));
        }

        return $date ?: null;
    }
}
