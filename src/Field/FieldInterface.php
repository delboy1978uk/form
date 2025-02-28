<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Collection\FilterCollection;
use Del\Form\Collection\ValidatorCollection;
use Del\Form\Filter\FilterInterface;
use Del\Form\FormInterface;
use Del\Form\Validator\ValidatorInterface;
use Del\Form\Renderer\Field\FieldRendererInterface;
use Exception;

interface FieldInterface
{
    public function setValue(mixed $value): void;
    public function getValue(): mixed;
    public function setLabel(string $label): void;
    public function getLabel(): ?string;
    public function getId(): ?string;
    public function getName(): string;
    public function getTag(): string;
    public function getClass(): string;
    public function addValidator(ValidatorInterface $validator): void;
    public function getValidators(): ValidatorCollection;
    public function addFilter(FilterInterface $filter): void;
    public function getFilters(): FilterCollection;
    public function setTransformer(TransformerInterface $transformer): void;
    public function getTransformer(): TransformerInterface;
    public function hasTransformer(): bool;
    public function isValid(): bool;
    public function getMessages(): array;
    public function setCustomErrorMessage(string $message): void;
    public function hasCustomErrorMessage(): bool;
    public function getCustomErrorMessage(): string;
    public function getAttribute(string $key);
    public function setAttribute(string $key, $value): void;
    public function setAttributes(array $attributes): void;
    public function getAttributes(): array;
    public function getRenderer(): FieldRendererInterface;
    public function setRenderer(FieldRendererInterface $renderer): void;
    public function init();
    public function setRequired(bool $required): void;
    public function addDynamicForm(FormInterface $field, string $triggerValue): void;
    public function hasDynamicForms(): bool;
    public function getDynamicForms(): array;
    public function isRequired(): bool;
}
