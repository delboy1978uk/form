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
use Del\Form\Validator\ValidatorInterface;
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
     * @return mixed
     */
    public function getTagType();

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
     * @return bool
     * @throws Exception If validation of $value is impossible
     */
    public function isValid();

    /**
     * @return array
     */
    public function getMessages();
}