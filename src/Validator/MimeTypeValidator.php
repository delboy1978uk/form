<?php

namespace Del\Form\Validator;

class MimeTypeValidator implements ValidatorInterface
{
    /** @var array $validMimeTypes */
    private $validMimeTypes;

    /** @var string $name */
    private $name;

    /**
     * FileExtensionValidator constructor.
     * @param array $validMimeTypes
     */
    public function __construct(array $validMimeTypes, string $name)
    {
        $this->validMimeTypes = $validMimeTypes;
        $this->name = $name;
    }

    /**
     * @param mixed $value
     * @return bool|void
     */
    public function isValid($value)
    {
        $files = $_FILES;
        $mimeType = isset($files[$this->name]) ? mime_content_type($files[$this->name]['tmp_name']) : '';

        return in_array($mimeType, $this->validMimeTypes);
    }

    /**
     * @return array|void
     */
    public function getMessages()
    {
        return ['Invalid mime type.'];
    }
}
