<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Collection\ValidatorCollection;
use Del\Form\FormInterface;

class SubForm extends FieldAbstract
{
    private FormInterface $form;

    public function __construct(string $name, FormInterface $form, $value = null)
    {
        parent::__construct($name, $value);
        $this->form = $form;
    }

    public function getTag(): string
    {
        return 'textarea';
    }

    public function init() {}

    public function setValue($value): void
    {
        if (is_string($value)) {
            $data = json_decode($value, true);
        }

        if (is_array($value)) {
            $this->form->populate($value);
        }
    }

    public function getValue(): FormInterface
    {
        return $this->form;
    }

    public function isValid(): bool
    {
        return $this->form->isValid();
    }

    public function getMessages(): array
    {
        return $this->form->getErrorMessages();
    }
}
