<?php

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

    /**  @var TransformerInterface $transformer */
    private $transformer;

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
    abstract public function getTag(): string;

    abstract public function init();

    public function __construct($name, $value = null)
    {
        $this->required = false;
        $this->dynamicFormCollection = [];
        $this->filterCollection = new FilterCollection();
        $this->validatorCollection = new ValidatorCollection();
        $this->renderer = new TextRender();
        $this->setName($name);
        $value === null ?: $this->setValue($value);
        $this->init();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->setAttribute('name', $name);
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->setAttribute('id', $id);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->getAttribute('class') ?: 'form-control';
    }

    /**
     * @param string $class
     * @return FieldAbstract
     */
    public function setClass(string $class): void
    {
        $this->setAttribute('class', $class);
    }

    public function getValue(): mixed
    {
        return $this->getAttribute('value');
    }

    /**
     * @param string $value
     */
    public function setValue($value): void
    {
        $this->setAttribute('value', $value);
        $this->filterValue();
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function addValidator(ValidatorInterface $validator): void
    {
        $this->validatorCollection->append($validator);
    }

    /**
     * @return ValidatorCollection
     */
    public function getValidators(): ValidatorCollection
    {
        return $this->validatorCollection;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter): void
    {
        $this->filterCollection->append($filter);
    }

    /**
     * @param FilterInterface $transformer
     */
    public function setTransformer(TransformerInterface $transformer): void
    {
        $this->transformer = $transformer;
    }

    /**
     * @return TransformerInterface
     */
    public function getTransformer(): TransformerInterface
    {
        return $this->transformer;
    }

    /**
     * @return bool
     */
    public function hasTransformer(): bool
    {
        return $this->transformer instanceof TransformerInterface;
    }

    /**
     * @return FilterCollection
     */
    public function getFilters(): FilterCollection
    {
        return $this->filterCollection;
    }

    /**
     *  Runs the checkForErrors method for each field, which adds to errorMessages if invalid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $this->errorMessages = [];
        $this->validatorCollection->rewind();

        while ($this->validatorCollection->valid()) {
            $this->checkForErrors($this->validatorCollection->current());
            $this->validatorCollection->next();
        }

        $count = \count($this->errorMessages);

        return $count == 0;
    }

    /**
     * @param ValidatorInterface $validator
     */
    private function checkForErrors(ValidatorInterface $validator): void
    {
        $value = $this->getValue();

        if ((!$validator->isValid($value)) && $this->isRequired()) {
            $this->errorMessages = array_merge($this->errorMessages, $validator->getMessages());
        }
    }

    /**
     * @throws Exception
     */
    private function filterValue(): void
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
    public function getMessages(): array
    {
        return isset($this->customErrorMessage) ? [$this->customErrorMessage] : array_values($this->errorMessages);
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @param string $message
     */
    public function setCustomErrorMessage(string $message): void
    {
        $this->customErrorMessage = $message;
    }

    /**
     * @return bool
     */
    public function hasCustomErrorMessage(): bool
    {
        return $this->customErrorMessage != null;
    }

    /**
     * @return string
     */
    public function getCustomErrorMessage(): string
    {
        return $this->customErrorMessage;
    }

    /**
     * @return FieldRendererInterface
     */
    public function getRenderer(): FieldRendererInterface
    {
        return $this->renderer;
    }

    /**
     * @param FieldRendererInterface $renderer
     */
    public function setRenderer(FieldRendererInterface $renderer): void
    {
        $this->renderer = $renderer;
    }

    /**
     * If a field is required then it must have a value
     * We add a not empty validator
     *
     * @return boolean
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     */
    public function setRequired(bool $required): void
    {
        $required ? $this->addNotEmptyValidator() : $this->removeNotEmptyValidator();
        $this->required = $required;
    }

    /**
     * adds not empty validator
     */
    private function addNotEmptyValidator(): void
    {
        $notEmpty = new NotEmpty();
        $this->addValidator($notEmpty);
    }

    /**
     *  removes not empty validator
     */
    private function removeNotEmptyValidator(): void
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


    public function addDynamicForm(FormInterface $form, string $triggerValue): void
    {
        $this->dynamicFormCollection[$triggerValue] = $form;
    }

    /**
     * @return bool
     */
    public function hasDynamicForms(): bool
    {
        return count($this->dynamicFormCollection) > 0;
    }

    /**
     * @return FormInterface[]
     * @throws Exception
     */
    public function getDynamicForms(): array
    {
        if (!$this->hasDynamicForms()) {
            throw new Exception('No dynamic form for this value - Did you check hasDynamicForm() ?');
        }
        return $this->dynamicFormCollection;
    }
}
