<?php

namespace Del\Form\Field;

use Del\Form\Renderer\Field\FileUploadRender;
use InvalidArgumentException;
use LogicException;

class FileUpload extends FieldAbstract implements FieldInterface
{
    /** @var string $uploadDirectory */
    private $uploadDirectory;

    /** @var array $_FILES */
    private $files;

    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'input';
    }

    public function init(): void
    {
        $this->setAttribute('type', 'file');
        $this->setRenderer(new FileUploadRender());
        $this->files = $_FILES;

        if ($this->hasUploadedFile()) {
            $this->setValue($this->files[$this->getName()]['name']);
        }
    }

    /**
     * @return bool
     */
    private function hasUploadedFile(): bool
    {
        return $this->isFileArraySet() && $this->isTempNameSet();
    }

    /**
     * @return bool
     */
    private function isFileArraySet(): bool
    {
        return isset($this->files[$this->getName()]);
    }

    /**
     * @return bool
     */
    private function isTempNameSet(): bool
    {
        return isset($this->files[$this->getName()]['tmp_name']);
    }

    /**
     * @param $path
     */
    public function setUploadDirectory(string $path): void
    {
        $path = realpath($path);

        if (!is_dir($path) || !is_writable($path)) {
            throw new InvalidArgumentException('Directory does not exist or is not writable.');
        }

        $this->uploadDirectory = $path;
    }

    /**
     * @return string
     */
    public function getUploadDirectory(): string
    {
        return $this->uploadDirectory;
    }

    /**
     * @return bool
     */
    public function hasUploadDirectory(): bool
    {
        return $this->uploadDirectory !== null;
    }

    /**
     * @return bool
     */
    public function moveUploadToDestination(): bool
    {
        if (!$this->hasUploadDirectory()) {
            throw new LogicException('No destination directory set using setUploadDirectory($path)');
        }

        $tmp = $this->files[$this->getName()]['tmp_name'];
        $destination = $this->getUploadDirectory().DIRECTORY_SEPARATOR.$this->files[$this->getName()]['name'];
        $success = move_uploaded_file($tmp, $destination);

        return $success;
    }
}