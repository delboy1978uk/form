<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 12:16
 */

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
     * @return FieldInterface
     */
    public function setValue($value);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param string $label
     * @return FieldInterface
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getTag();

    /**
     * @return mixed
     */
    public function getClass();

    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function addValidator(ValidatorInterface $validator);

    /**
     * @return ValidatorCollection
     */
    public function getValidators();

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function addFilter(FilterInterface $filter);

    /**
     * @return FilterCollection
     */
    public function getFilters();

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function setTransformer(TransformerInterface $transformer);

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
    public function isValid();

    /**
     * @return array
     */
    public function getMessages();

    /**
     * @param string $message
     * @return FieldInterface
     */
    public function setCustomErrorMessage($message);

    /**
     * @return bool
     */
    public function hasCustomErrorMessage();

    /**
     * @return string
     */
    public function getCustomErrorMessage();

    /**
     * @param $key
     * @return mixed|string
     */
    public function getAttribute($key);

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setAttribute($key, $value);

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @return FieldRendererInterface
     */
    public function getRenderer();

    /**
     * @param FieldRendererInterface $renderer
     * @return $this
     */
    public function setRenderer(FieldRendererInterface $renderer);

    public function init();

    /**
     * @param bool $required
     * @return $this
     */
    public function setRequired($required);

    /**
     * @param FormInterface $field
     * @param $triggerValue
     * @return $this
     */
    public function addDynamicForm(FormInterface $field, $triggerValue);

    /**
     * @return bool
     */
    public function hasDynamicForms();

    /**
     * @return FormInterface[]
     */
    public function getDynamicForms();

    /**
     * @return bool
     */
    public function isRequired();
}