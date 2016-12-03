<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:41
 */

namespace Del\Form\Field;

use Del\Form\Collection\FilterCollection;
use Del\Form\Collection\ValidatorCollection;
use Del\Form\Filter\FilterInterface;
use Del\Form\Validator\ValidatorInterface;
use Exception;

abstract class FieldAbstract implements FieldInterface
{
    /** @var string $name */
    private $name;

    /** @var string $id */
    private $id;

    /** @var string $class  */
    private $class;

    /**  @var FilterCollection $filterCollection */
    private $filterCollection;

    /**  @var ValidatorCollection $validatorCollection */
    private $validatorCollection;

    private $value;

    /** @var array $errorMessages */
    private $errorMessages;

    /** @var string $customErrorMessage */
    private $customErrorMessage;

    /** @var string $label */
    private $label;

    /**
     * @return string
     */
    abstract public function getTag();

    /**
     * @return mixed
     */
    abstract public function getTagType();

    public function __construct($name, $value = null)
    {
        $this->filterCollection = new FilterCollection();
        $this->validatorCollection = new ValidatorCollection();
        $this->setName($name);
        is_null($value) ? null : $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FieldAbstract
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return FieldAbstract
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class ?: 'form-control';
    }

    /**
     * @param string $class
     * @return FieldAbstract
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return FieldAbstract
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->filterValue();
        return $this;
    }

    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function addValidator(ValidatorInterface $validator)
    {
        $this->validatorCollection->append($validator);
        return $this;
    }

    /**
     * @return ValidatorCollection
     */
    public function getValidators()
    {
        return $this->validatorCollection;
    }

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filterCollection->append($filter);
        return $this;
    }

    /**
     * @return FilterCollection
     */
    public function getFilters()
    {
        return $this->filterCollection;
    }

    /**
     * @return bool
     * @throws Exception If validation of $value is impossible
     */
    public function isValid()
    {
        $this->errorMessages = [];
        $this->validatorCollection->rewind();
        while ($this->validatorCollection->valid()) {
            $this->checkForErrors($this->validatorCollection->current());
            $this->validatorCollection->next();
        }
        $count = count($this->errorMessages);
        return $count == 0;
    }

    /**
     * @param FieldInterface $field
     */
    private function checkForErrors(ValidatorInterface $validator)
    {
        $value = $this->getValue();

        if (!$validator->isValid($value)) {
            $this->errorMessages = array_merge($this->errorMessages, $validator->getMessages());
        }
    }

    private function filterValue()
    {
        $value = $this->value;
        $this->filterCollection->rewind();
        while ($this->filterCollection->valid()) {
            $value = $this->filterCollection->current()->filter($value);
            $this->filterCollection->next();
        }
        $this->filterCollection->rewind();
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return array_values($this->errorMessages);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return FieldAbstract
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setCustomErrorMessage($message)
    {
        $this->customErrorMessage = $message;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasCustomErrorMessage()
    {
        return $this->customErrorMessage != null;
    }

    /**
     * @return string
     */
    public function getCustomErrorMessage()
    {
        return $this->customErrorMessage;
    }


}