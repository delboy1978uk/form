<?php

declare(strict_types=1);

namespace Del\Form\Validator;

use function mime_content_type;

class MimeTypeValidator implements ValidatorInterface
{
    public function __construct(
        private array $validMimeTypes,
        private string $name
    ) {}

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
