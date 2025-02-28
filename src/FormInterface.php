<?php

declare(strict_types=1);

namespace Del\Form;

use Del\Form\Collection\FieldCollection;
use Del\Form\Field\FieldInterface;

interface FormInterface
{
    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @param bool $transform
     * @return array
     */
    public function getValues(bool $transform = false): array;

    /**
     * @param array $values
     */
    public function populate(array $values): void;

    /**
     * @param string $name
     * @return null|FieldInterface
     */
    public function getField(string $name): ?FieldInterface;

    /**
     * @return FieldCollection
     */
    public function getFields(): FieldCollection;

    /**
     * @param FieldInterface $field
     */
    public function addField(FieldInterface $field): void;

    /**
     * @return string
     */
    public function render(): string;

    /**
     * @param string $url
     */
    public function setAction(string $url): void;

    /**
     * @return string
     */
    public function getAction(): string;

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @param string $id
     */
    public function setId(string $id): void;

    /**
     * @param string $encType
     */
    public function setEncType(string $encType): void;

    /**
     * @return string
     */
    public function getEncType(): string;

    /**
     * @param string $method
     */
    public function setMethod(string $method): void;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @param string $class
     */
    public function setClass(string $class): void;

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key);

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute(string $key, mixed $value): void;

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @return boolean
     */
    public function isDisplayErrors(): bool;

    /**
     * @param boolean $displayErrors
     * @return AbstractForm
     */
    public function setDisplayErrors(bool $displayErrors): void;
}
