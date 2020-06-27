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
        $mimeType = isset($_FILES[$this->name]) ? mime_content_type($_FILES[$this->name]['tmp_name']) : '';

        return in_array($mimeType, $this->validMimeTypes);
    }

    /**
     * @return array|void
     */
    public function getMessages()
    {
        return ['Invalid file type.'];
    }


    /**
     * @param string $path
     * @return string
     */
    private function getMimeType(string $path): string
    {
        $finfo = finfo_open(FILEINFO_MIME); // return mime type
        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);

        return $mimeType;
    }
}
