<?php

namespace Del\Form\Validator;

class FileExtensionValidator implements ValidatorInterface
{
    /** @var array $validExtensions */
    private $validExtensions;

    /**
     * FileExtensionValidator constructor.
     * @param array $validExtensions
     */
    public function __construct(array $validExtensions)
    {
        $this->validExtensions = $validExtensions;
    }

    /**
     * @param mixed $value
     * @return bool|void
     */
    public function isValid($value)
    {
        $debris = explode('.', $value);
        $extension = end($debris);
        $extension = strtolower($extension);

        return in_array($extension, $this->validExtensions);
    }

    /**
     * @return array|void
     */
    public function getMessages()
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