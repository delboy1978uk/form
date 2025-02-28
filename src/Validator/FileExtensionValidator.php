<?php

declare(strict_types=1);

namespace Del\Form\Validator;

class FileExtensionValidator implements ValidatorInterface
{
    private array $validExtensions;

    public function __construct(array $validExtensions)
    {
        $this->validExtensions = $validExtensions;
    }

    public function isValid(mixed $value): bool
    {
        $debris = explode('.', $value);
        $extension = end($debris);
        $extension = strtolower($extension);

        return in_array($extension, $this->validExtensions);
    }

    public function getMessages(): array
    {
        $validExtensions = $this->validExtensions;
        $last = array_pop($validExtensions);
        $extensions = 'The file must be a ' . $last . ' file.';

        if (count($validExtensions) > 0) {
            $extensions = implode(', ', $validExtensions);
            $extensions .= ' or ' . $last;
            $extensions = 'The file must be either a ' . $extensions . ' file.';
        }

        return [$extensions];
    }
}
