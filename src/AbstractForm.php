<?php

namespace Del\Form;

use Del\Form\Collection\FieldCollection;
use Del\Form\Field\CheckBox;
use Del\Form\Field\FieldInterface;
use Del\Form\Field\FileUpload;
use Del\Form\Renderer\FormRenderer;
use Del\Form\Renderer\FormRendererInterface;
use Del\Form\Traits\HasAttributesTrait;

abstract class AbstractForm implements FormInterface
{
    const ENC_TYPE_MULTIPART_FORM_DATA = 'multipart/form-data';
    const ENC_TYPE_URL_ENCODED = 'application/x-www-form-urlencoded';
    const ENC_TYPE_TEXT_PLAIN = 'text/plain';

    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    /** @var FieldCollection $fieldCollection */
    private $fieldCollection;

    /** @var FormRendererInterface  */
    private $formRenderer;

    /** @var array $errorMessages */
    private $errorMessages;

    /** @var bool $displayErrors */
    private $displayErrors;

    use HasAttributesTrait;

    /**
     * AbstractForm constructor.
     * @param $name
     */
    public function __construct(string $name)
    {
        $this->fieldCollection = new FieldCollection();
        $this->formRenderer = new FormRenderer();
        $this->attributes = [
            'name' => $name,
            'method' => self::METHOD_POST,
        ];
        $this->displayErrors = false;
        $this->init();
    }

    abstract public function init();

    /**
     * @return bool
     * @throws \Exception
     */
    public function isValid(): bool
    {
        $this->errorMessages = [];
        $fields = $this->fieldCollection;
        $this->validateFields($fields);
        $count = count($this->errorMessages);
        $valid = ($count == 0);
        if ($valid) {
            $this->moveUploadedFiles();
        }
        return $valid;
    }

    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * @param FieldCollection $fields
     * @throws \Exception
     */
    private function validateFields(FieldCollection $fields): void
    {
        $fields->rewind();
        while ($fields->valid()) {
            $this->checkFieldForErrors($fields->current());
            $this->checkDynamicFormsForErrors($fields->current());
            $fields->next();
        }
        $fields->rewind();
    }

    /**
     * @param FieldInterface $field
     * @throws \Exception
     */
    private function checkFieldForErrors(FieldInterface $field): void
    {
        if (!$field->isValid()) {
            $this->errorMessages[$field->getName()] = $field->getMessages();
        }
    }

    /**
     * @param FieldInterface $field
     */
    public function checkDynamicFormsForErrors(FieldInterface $field): void
    {
        if ($field->hasDynamicForms()) {
            $forms = $field->getDynamicForms();
            $value = $field->getValue();
            if (isset($forms[$value])) {
                $form = $forms[$value];
                $fields = $form->getFields();
                $this->validateFields($fields);
            }
        }
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        $values = [];
        $fields = $this->fieldCollection;
        $values = $this->getFieldValues($fields, $values);
        
        return $values;
    }

    /**
     * @param FieldCollection $fields
     * @param array $values
     * @return array
     */
    private function getFieldValues(FieldCollection $fields, array $values): array
    {
        $fields->rewind();

        while ($fields->valid()) {
            /** @var FieldInterface $field */
            $field = $fields->current();
            $value = $field->getValue();

            if ($field->hasTransformer()) {
                $value = $field->getTransformer()->output($value);
            }

            $values[$field->getName()] = $value;

            if ($field->hasDynamicForms()) {
                $forms = $field->getDynamicForms();
                if (isset($forms[$value])) {
                    $form = $forms[$value];
                    $dynamicFormFields = $form->getFields();
                    $values = $this->getFieldValues($dynamicFormFields, $values);
                }
            }

            $fields->next();
        }

        $fields->rewind();

        return $values;
    }

    /**
     * @param array $data
     */
    public function populate(array $data): void
    {
        $fields = $this->fieldCollection;
        $this->populateFields($fields, $data);
        $this->displayErrors = true;
    }

    /**
     * @param array $dynamicForms
     * @param array $data
     */
    private function populateDynamicForms(array $dynamicForms, array $data): void
    {
        /** @var FormInterface $form **/
        foreach ($dynamicForms as $form) {
            $fields = $form->getFields();
            $this->populateFields($fields, $data);
        }
    }

    /**
     * @param FieldCollection $fields
     * @param array $data
     */
    private function populateFields(FieldCollection $fields, array $data): void
    {
        $fields->rewind();
        while ($fields->valid()) {
            $field = $fields->current();
            $this->populateField($field, $data);
            $fields->next();
        }
        $fields->rewind();
    }

    /**
     * @param FieldInterface $field
     * @param array $data
     */
    private function populateField(FieldInterface $field, array $data): void
    {
        $name = $field->getName();

        if (isset($data[$name]) && $field->hasTransformer()) {
            $value = $field->getTransformer()->input($data[$name]);
            $field->setValue($value);
        } elseif (isset($data[$name])) {
            $field->setValue($data[$name]);
        } elseif ($field instanceof CheckBox) {
            $field->setValue(false);
        }

        if ($field->hasDynamicForms()) {
            $forms = $field->getDynamicForms();
            $this->populateDynamicForms($forms, $data);
        }
    }

    /**
     * @param string $name
     * @return FieldInterface|null
     */
    public function getField($name): ?FieldInterface
    {
        return $this->fieldCollection->findByName($name);
    }

    /**
     * @return FieldCollection
     */
    public function getFields(): FieldCollection
    {
        return $this->fieldCollection;
    }

    /**
     * @param FieldInterface $field
     */
    public function addField(FieldInterface $field): void
    {
        $this->fieldCollection->append($field);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->formRenderer->render($this, $this->isDisplayErrors());
    }

    /**
     * @param $url
     */
    public function setAction($url): void
    {
        $this->setAttribute('action', $url);
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->getAttribute('action');
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
     * @param $encType
     */
    public function setEncType(string $encType): void
    {
        $this->setAttribute('enctype', $encType);
    }

    /**
     * @return string
     */
    public function getEncType(): string
    {
        return $this->getAttribute('enctype');
    }

    /**
     * @param string $method
     * @return FormInterface
     */
    public function setMethod(string $method): void
    {
        $this->setAttribute('method', $method);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->getAttribute('method');
    }

    /**
     * @param $class
     */
    public function setClass(string $class): void
    {
        $this->setAttribute('class', $class);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->getAttribute('class');
    }

    /**
     * @return boolean
     */
    public function isDisplayErrors(): bool
    {
        return $this->displayErrors;
    }

    /**
     * @param boolean $displayError
     */
    public function setDisplayErrors($displayErrors): void
    {
        $this->displayErrors = $displayErrors;
    }

    /**
     * @param FormRendererInterface $renderer
     * @return AbstractForm
     */
    public function setFormRenderer(FormRendererInterface $renderer): AbstractForm
    {
        $this->formRenderer = $renderer;

        return $this;
    }

    public function moveUploadedFiles(): void
    {
        $this->fieldCollection->rewind();
        while ($this->fieldCollection->valid()) {
            $current = $this->fieldCollection->current();
            $this->moveFileIfUploadField($current);
            $this->fieldCollection->next();
        }
    }

    /**
     * @param FieldInterface $field
     * @return bool
     */
    public function moveFileIfUploadField(FieldInterface $field): bool
    {
        if ($field instanceof FileUpload) {
            $field->moveUploadToDestination();
            return true;
        }
        return false;
    }
}