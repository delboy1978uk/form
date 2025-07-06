<?php

declare(strict_types=1);

namespace Del\Form\Traits;

use Del\Form\Field\Attributes\Field;
use Del\Form\FormInterface;
use ReflectionClass;
use function array_shift;
use function count;
use function explode;
use function ucfirst;

trait HasFormFields
{
    public function populate(FormInterface $form): void
    {
        $data = $form->getValues();
        $mirror = new ReflectionClass($this);
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

                $value = $data[$fieldName];
                $this->setField($fieldName, $fieldType, $value);
            }
        }
    }

    private function setField(string $fieldName, string $fieldType, mixed $value): void
    {
        $setter = 'set' . ucfirst($fieldName);

        switch ($fieldType) {
            case 'checkbox':
                $this->$setter((bool) $value);
                break;
            case 'email':
            case 'file':
            case 'hidden':
            case 'password':
            case 'string':
            case 'text':
            case 'textarea':
                $this->$setter((string) $value);
                break;
            case 'integer':
                $this->$setter((int) $value);
                break;
            case 'float':
                $this->$setter((float) $value);
                break;
            case 'multiselect':
            case 'radio':
            case 'select':
            default:
                $this->$setter($value);
                break;
        }
    }
}
