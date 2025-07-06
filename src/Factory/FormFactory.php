<?php

declare(strict_types=1);

namespace Del\Form\Factory;

use Del\Form\Field\Attributes\Field;
use Del\Form\Field\CheckBox;
use Del\Form\Field\FieldInterface;
use Del\Form\Field\FileUpload;
use Del\Form\Field\Hidden;
use Del\Form\Field\MultiSelect;
use Del\Form\Field\Radio;
use Del\Form\Field\Select;
use Del\Form\Field\Submit;
use Del\Form\Field\Text;
use Del\Form\Field\Text\EmailAddress;
use Del\Form\Field\Text\FloatingPoint;
use Del\Form\Field\Text\Integer;
use Del\Form\Field\Text\Password;
use Del\Form\Field\TextArea;
use Del\Form\Form;
use Del\Form\FormInterface;
use Del\Form\Validator\FileExtensionValidator;
use Del\Form\Validator\MaxLength;
use Del\Form\Validator\MimeTypeValidator;
use Del\Form\Validator\MinLength;
use ReflectionClass;
use function array_shift;
use function count;
use function explode;
use function strpos;

class FormFactory
{
    private Form $form;

    public function createFromEntity(string $name, object $entity): FormInterface
    {
        $this->form = new Form($name);
        $mirror = new ReflectionClass($entity);
        $properties = $mirror->getProperties();

        foreach ($properties as $property) {
            $fieldName = $property->getName();
            $attributes = $property->getAttributes(Field::class);

            if (count($attributes) > 0) {
                $rules = $attributes[0]->newInstance()->rules;

                if (strpos($rules, '|') !== false) {
                    $rules = explode('|', $rules);
                    $fieldType = array_shift($rules);
                } else {
                    $fieldType = $rules;
                    $rules = [];
                }

                $field = $this->createField($fieldName, $fieldType);
                $this->addValidators($field, $rules);
                $value = $property->getValue($entity);
                $this->setValue($field, $value);
                $this->form->addField($field);
            }
        }

        $this->form->addField(new Submit('submit'));

        return $this->form;
    }

    private function createField(string $fieldName, string $fieldType): FieldInterface
    {
        switch ($fieldType) {
            case 'checkbox':
                $field = new CheckBox($fieldName);
                break;
            case 'email':
                $field = new EmailAddress($fieldName);
                break;
            case 'file':
                $field = new FileUpload($fieldName);
                break;
            case 'float':
                $field = new FloatingPoint($fieldName);
                break;
            case 'hidden':
                $field = new Hidden($fieldName);
                break;
            case 'integer':
                $field = new Integer($fieldName);
                break;
            case 'multiselect':
                $field = new MultiSelect($fieldName);
                break;
            case 'password':
                $field = new Password($fieldName);
                break;
            case 'radio':
                $field = new Radio($fieldName);
                break;
            case 'select':
                $field = new Select($fieldName);
                break;
            case 'textarea':
                $field = new TextArea($fieldName);
                break;
            case 'text':
            default:
                $field = new Text($fieldName);
                break;
        }

        return $field;
    }

    private function addValidators(FieldInterface $field, array $rules): void
    {
        foreach ($rules as $rule) {
            $arg = null;

            if (strpos($rule, ':') !== false) {
                $debris = explode(':', $rule);
                $rule = $debris[0];
                $arg = $debris[1];
            }

            switch ($rule) {
                case 'file_ext':
                    $extensions = explode(',', $arg);
                    $field->addValidator(new FileExtensionValidator($extensions));
                    break;
                case 'max':
                    $field->addValidator(new MaxLength((int) $arg));
                    break;
                case 'mime':
                    $mimeTypes = explode(',', $arg);
                    $field->addValidator(new MimeTypeValidator($mimeTypes, $field->getName()));
                    break;
                case 'min':
                    $field->addValidator(new MinLength((int) $arg));
                    break;
                case 'required':
                    $field->setRequired(true);
                    break;
            }
        }
    }

    private function setValue(FieldInterface $field, mixed $value): void
    {
        if ($value !== null) {
            $field->setValue($value);
        }
    }
}
