<?php

declare(strict_types=1);

namespace Del\Form\Traits;

use Closure;
use Del\Form\Field\Attributes\Field;
use Del\Form\FormInterface;
use ReflectionClass;
use function array_shift;
use function count;
use function explode;
use function str_contains;
use function ucfirst;

trait HasFormFields
{
    private ?Closure $findEntity = null;

    public function setFindEntity(?Closure $findEntity): void
    {
        $this->findEntity = $findEntity;
    }

    public function populate(FormInterface $form): void
    {
        $data = $form->getValues(true);
        $mirror = new ReflectionClass($this);
        $properties = $mirror->getProperties();

        foreach ($properties as $property) {
            $fieldName = $property->getName();
            $attributes = $property->getAttributes(Field::class);

            if (count($attributes) > 0) {
                $instance = $attributes[0]->newInstance();
                $rules = $instance->rules;
                $fieldClass = $instance->fieldClass;

                if (str_contains($rules, '|')) {
                    $rules = explode('|', $rules);
                    $fieldType = array_shift($rules);
                } else {
                    $fieldType = $rules;
                    $rules = [];
                }

                if (str_contains($fieldType, 'date_format')) {
                    $fieldType = str_contains($fieldType, 'H:i')
                        ? 'datetime'
                        : 'date';
                }

                $value = $data[$fieldName];
                $this->setField($form, $fieldName, $fieldType, $value, $fieldClass);
            }
        }
    }

    private function setField(FormInterface $form, string $fieldName, string $fieldType, mixed $value, ?string $fieldClass = null): void
    {
        $setter = 'set' . ucfirst($fieldName);

        switch ($fieldType) {
            case 'checkbox':
                $this->$setter((bool) $value);
                break;
            case 'email':
                $this->$setter((string) $value);
                break;
            case 'entity':
                $entityForm= $form->getField($fieldName)->getValue();
                $values = $entityForm->getValues();

                if (isset($values['id']) && $this->findEntity !== null) {
                    $finder = $this->findEntity;
                    $entity = $finder($values['id']);
                } else {
                    $entity = new $fieldClass();
                    $entity->populate($entityForm);
                }

                $this->$setter($entity);

                break;
            case 'file':
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
            case 'date':
            case 'datetime':
            case 'multiselect':
            case 'radio':
            case 'select':
            default:
                $this->$setter($value);
                break;
        }
    }
}
