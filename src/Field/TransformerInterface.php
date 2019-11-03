<?php

namespace Del\Form\Field;

interface TransformerInterface
{
    /**
     * @param mixed $data
     * @return string
     */
    public function input($data): string;

    /**
     * @return mixed
     */
    public function output(string $value);
}