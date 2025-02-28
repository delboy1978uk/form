<?php

declare(strict_types=1);

namespace Del\Form;

use Del\Form\Collection\FieldCollection;
use Del\Form\Field\FieldInterface;

interface FormInterface
{
    public function isValid(): bool;
    public function getValues(bool $transform = false): array;
    public function populate(array $values): void;
    public function getField(string $name): ?FieldInterface;
    public function getFields(): FieldCollection;
    public function addField(FieldInterface $field): void;
    public function render(): string;
    public function setAction(string $url): void;
    public function getAction(): string;
    public function getId(): ?string;
    public function setId(string $id): void;
    public function setEncType(string $encType): void;
    public function getEncType(): string;
    public function setMethod(string $method): void;
    public function getMethod(): string;
    public function setClass(string $class): void;
    public function getClass(): string;
    public function getAttribute(string $key): mixed;
    public function setAttribute(string $key, mixed $value): void;
    public function setAttributes(array $attributes): void;
    public function getAttributes(): array;
    public function isDisplayErrors(): bool;
    public function setDisplayErrors(bool $displayErrors): void;
}
