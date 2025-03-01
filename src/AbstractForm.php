<?php

declare(strict_types=1);

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

    private FieldCollection $fieldCollection;
    private FormRendererInterface $formRenderer;
    private array $errorMessages;
    private bool $displayErrors;

    use HasAttributesTrait;

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

    abstract public function init(): void;

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

    private function checkFieldForErrors(FieldInterface $field): void
    {
        if (!$field->isValid()) {
            $this->errorMessages[$field->getName()] = $field->getMessages();
        }
    }

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

    public function getValues(bool $transform = false): array
    {
        $values = [];
        $fields = $this->fieldCollection;
        $values = $this->getFieldValues($fields, $values, $transform);

        return $values;
    }

    private function getFieldValues(FieldCollection $fields, array $values, bool $transform): array
    {
        $fields->rewind();

        while ($fields->valid()) {
            /** @var FieldInterface $field */
            $field = $fields->current();
            $value = $field->getValue();

            if ($transform && $field->hasTransformer()) {
                $value = $field->getTransformer()->output($value);
            }

            $values[$field->getName()] = $value;

            if ($field->hasDynamicForms()) {
                $forms = $field->getDynamicForms();
                if (isset($forms[$value])) {
                    $form = $forms[$value];
                    $dynamicFormFields = $form->getFields();
                    $values = $this->getFieldValues($dynamicFormFields, $values, $transform);
                }
            }

            $fields->next();
        }

        $fields->rewind();

        return $values;
    }

    public function populate(array $values): void
    {
        $fields = $this->fieldCollection;
        $this->populateFields($fields, $values);
        $this->displayErrors = true;
    }

    private function populateDynamicForms(array $dynamicForms, array $data): void
    {
        /** @var FormInterface $form **/
        foreach ($dynamicForms as $form) {
            $fields = $form->getFields();
            $this->populateFields($fields, $data);
        }
    }

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

    public function getField(string $name): ?FieldInterface
    {
        return $this->fieldCollection->findByName($name);
    }

    public function getFields(): FieldCollection
    {
        return $this->fieldCollection;
    }

    public function addField(FieldInterface $field): void
    {
        $this->fieldCollection->append($field);
    }

    public function render(): string
    {
        return $this->formRenderer->render($this, $this->isDisplayErrors());
    }

    public function setAction(string $url): void
    {
        $this->setAttribute('action', $url);
    }

    public function getAction(): string
    {
        return $this->getAttribute('action');
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    public function setId(string $id): void
    {
        $this->setAttribute('id', $id);
    }

    public function setEncType(string $encType): void
    {
        $this->setAttribute('enctype', $encType);
    }

    public function getEncType(): string
    {
        return $this->getAttribute('enctype');
    }

    public function setMethod(string $method): void
    {
        $this->setAttribute('method', $method);
    }

    public function getMethod(): string
    {
        return $this->getAttribute('method');
    }

    public function setClass(string $class): void
    {
        $this->setAttribute('class', $class);
    }

    public function getClass(): string
    {
        return $this->getAttribute('class');
    }

    public function isDisplayErrors(): bool
    {
        return $this->displayErrors;
    }

    public function setDisplayErrors(bool $displayErrors): void
    {
        $this->displayErrors = $displayErrors;
    }

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

    public function moveFileIfUploadField(FieldInterface $field): bool
    {
        if ($field instanceof FileUpload) {
            $field->moveUploadToDestination();
            return true;
        }
        return false;
    }


}
