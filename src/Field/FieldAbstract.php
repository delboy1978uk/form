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
use Del\Form\FormInterface;
use Del\Form\Renderer\Field\FieldRendererInterface;
use Del\Form\Renderer\Field\TextRender;
use Del\Form\Traits\HasAttributesTrait;
use Del\Form\Validator\NotEmpty;
use Del\Form\Validator\ValidatorInterface;
use Exception;

abstract class FieldAbstract implements FieldInterface
{

    /**  @var FormInterface[] $dynamicFormCollection */
    private $dynamicFormCollection;

    /**  @var FilterCollection $filterCollection */
    private $filterCollection;

    /**  @var ValidatorCollection $validatorCollection */
    private $validatorCollection;

    /** @var FieldRendererInterface $renderer  */
    private $renderer;

    /** @var array $errorMessages */
    private $errorMessages;

    /** @var string $customErrorMessage */
    private $customErrorMessage;

    /** @var string $label */
    private $label;

    /** @var bool $required */
    private $required;

    use HasAttributesTrait;

    /**
     * @return string
     */
    abstract public function getTag();

    abstract public function init();

    public function __construct($name, $value = null)
    {
        $this->required = false;
        $this->dynamicFormCollection = [];
        $this->filterCollection = new FilterCollection();
        $this->validatorCollection = new ValidatorCollection();
        $this->renderer = new TextRender();
        $this->setName($name);
        is_null($value) ? null : $this->setValue($value);
        $this->init();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @param string $name
     * @return FieldAbstract
     */
    public function setName($name)
    {
        $this->setAttribute('name', $name);
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @param string $id
     * @return FieldAbstract
     */
    public function setId($id)
    {
        $this->setAttribute('id', $id);
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->getAttribute('class') ?: 'form-control';
    }

    /**
     * @param string $class
     * @return FieldAbstract
     */
    public function setClass($class)
    {
        $this->setAttribute('class', $class);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->getAttribute('value');
    }

    /**
     * @param mixed $value
     * @return FieldAbstract
     */
    public function setValue($value)
    {
        $this->setAttribute('value', $value);
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
     *  Runs the checkForErrors method for each field, which adds to errorMessages if invalid
     *
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
     * @param ValidatorInterface $validator
     */
    private function checkForErrors(ValidatorInterface $validator)
    {
        $value = $this->getValue();

        if ( (!$validator->isValid($value)) && $this->isRequired()) {
            $this->errorMessages = array_merge($this->errorMessages, $validator->getMessages());
        }
    }

    private function filterValue()
    {
        $value = $this->getAttribute('value');
        $this->filterCollection->rewind();
        while ($this->filterCollection->valid()) {
            $value = $this->filterCollection->current()->filter($value);
            $this->filterCollection->next();
        }
        $this->filterCollection->rewind();
        $this->setAttribute('value', $value);
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
     * @return $this
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

    /**
     * @return FieldRendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param FieldRendererInterface $renderer
     * @return $this
     */
    public function setRenderer(FieldRendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * If a field is required then it must have a value
     * We add a not empty validator
     *
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     * @return FieldAbstract
     */
    public function setRequired($required)
    {
        $required ? $this->addNotEmptyValidator() : $this->removeNotEmptyValidator();
        $this->required = $required;
        return $this;
    }

    private function addNotEmptyValidator()
    {
        $notEmpty = new NotEmpty();
        $this->addValidator($notEmpty);
    }

    private function removeNotEmptyValidator()
    {
        $this->validatorCollection->rewind();
        while ($this->validatorCollection->valid()) {
            $validator = $this->validatorCollection->current();
            $validator instanceof NotEmpty
                ? $this->validatorCollection->offsetUnset($this->validatorCollection->key())
                : null;
            $this->validatorCollection->next();
        }
    }

    /**
     * @param FormInterface $form
     * @param $triggerValue
     * @return $this
     */
    public function addDynamicForm(FormInterface $form, $triggerValue)
    {
        $this->dynamicFormCollection[$triggerValue] = $form;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasDynamicForms()
    {
        return count($this->dynamicFormCollection) > 0;
    }

    /**
     * @return FormInterface[]
     * @throws Exception
     */
    public function getDynamicForms()
    {
        if (!$this->hasDynamicForms()) {
            throw new Exception('No dynamic form for this value - Did you check hasDynamicForm() ?');
        }
        return $this->dynamicFormCollection;
    }
}