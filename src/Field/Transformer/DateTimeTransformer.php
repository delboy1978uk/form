<?php

namespace Del\Form\Field\Transformer;

use DateTime;
use Del\Form\Field\TransformerInterface;

class DateTimeTransformer implements TransformerInterface
{
    /** @var string $dateFormat */
    private $dateFormat;

    /**
     * DateTimeTransformer constructor.
     * @param string $dateFormat
     */
    public function __construct(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function input($value): string
    {
        if ($value instanceof DateTime) {
            return $value->format($this->dateFormat);
        }

        return $value;
    }

    /**
     * @return DateTime
     */
    public function output(string $value)
    {
        return DateTime::createFromFormat($this->dateFormat, $value);
    }
}