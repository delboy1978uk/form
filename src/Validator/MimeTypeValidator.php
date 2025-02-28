<?php

declare(strict_types=1);

namespace Del\Form\Validator;

class MimeTypeValidator implements ValidatorInterface
{
    private array $validMimeTypes;
    private string $name = '';

    public function __construct(array $validMimeTypes, string $name)
    {
        $this->validMimeTypes = $validMimeTypes;
        $this->name = $name;
    }

    public function isValid(mixed $value): bool
    {
        $files = $_FILES;
        $mimeType = isset($files[$this->name]) ? mime_content_type($files[$this->name]['tmp_name']) : '';

        return in_array($mimeType, $this->validMimeTypes);
    }

    public function getMessages(): array
    {
        return ['Invalid mime type.'];
    }
}
