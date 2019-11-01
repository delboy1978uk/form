<?php

namespace Del\Form\Field;

use Del\Form\Collection\FilterCollection;
use Del\Form\Collection\ValidatorCollection;
use Del\Form\Filter\FilterInterface;
use Del\Form\FormInterface;
use Del\Form\Renderer\Field\SelectRender;
use Del\Form\Validator\ValidatorInterface;
use Del\Form\Renderer\Field\FieldRendererInterface;
use Exception;

interface FieldInterface
{
    /**
     * @param mixed $value
     */
    public function setValue($value): void;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param string $label
     */
    public function setLabel(string $label): void;

    /**
     * @return string
     */
    public function getLabel(): ?string;

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getTag(): string;

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @param ValidatorInterface $validator
     */
    public function addValidator(ValidatorInterface $validator): void;

    /**
     * @return ValidatorCollection
     */
    public function getValidators(): ValidatorCollection;

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter): void;

    /**
     * @return FilterCollection
     */
    public function getFilters(): FilterCollection;

    /**
     * @param FilterInterface $filter
     */
    public function setTransformer(TransformerInterface $transformer): void;

    /**
     * @return TransformerInterface
     */
    public function getTransformer(): TransformerInterface;

    /**
     * @return bool
     */
    public function hasTransformer(): bool;

    /**
     * @return bool
     * @throws Exception If validation of $value is impossible
     */
    public function isValid(): bool;

    /**
     * @return array
     */
    public function getMessages(): array;

    /**
     * @param string $message
     */
    public function setCustomErrorMessage(string $message): void;

    /**
     * @return bool
     */
    public function hasCustomErrorMessage(): bool;

    /**
     * @return string
     */
    public function getCustomErrorMessage(): string;

    /**
     * @param string $key
     */
    public function getAttribute(string $key);

    /**
     * @param $key
     * @param $value
     */
    public function setAttribute(string $key, string $value): void;

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @return FieldRendererInterface
     */
    public function getRenderer(): FieldRendererInterface;

    /**
     * @param FieldRendererInterface $renderer
     */
    public function setRenderer(FieldRendererInterface $renderer): void;

    public function init();

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void;

    /**
     * @param FormInterface $field
     * @param $triggerValue
     */
    public function addDynamicForm(FormInterface $field, string $triggerValue): void;

    /**
     * @return bool
     */
    public function hasDynamicForms(): bool;

    /**
     * @return FormInterface[]
     */
    public function getDynamicForms(): array;

    /**
     * @return bool
     */
    public function isRequired(): bool;
}