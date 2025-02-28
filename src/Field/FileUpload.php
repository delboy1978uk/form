<?php

declare(strict_types=1);

namespace Del\Form\Field;

use Del\Form\Renderer\Field\FileUploadRender;
use InvalidArgumentException;
use LogicException;

class FileUpload extends FieldAbstract
{
    private ?string $uploadDirectory = null;
    private array $files = [];

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

    private function hasUploadedFile(): bool
    {
        return $this->isFileArraySet() && $this->isTempNameSet();
    }

    private function isFileArraySet(): bool
    {
        return isset($this->files[$this->getName()]);
    }

    private function isTempNameSet(): bool
    {
        return isset($this->files[$this->getName()]['tmp_name']);
    }

    public function setUploadDirectory(string $path): void
    {
        $path = realpath($path);

        if (!is_dir($path) || !is_writable($path)) {
            throw new InvalidArgumentException('Directory ' . $path . ' does not exist or is not writable.');
        }

        $this->uploadDirectory = $path;
    }

    public function getUploadDirectory(): string
    {
        return $this->uploadDirectory;
    }

    public function hasUploadDirectory(): bool
    {
        return $this->uploadDirectory !== null;
    }

    public function moveUploadToDestination(): bool
    {
        if (!$this->hasUploadDirectory()) {
            throw new LogicException('No destination directory set using setUploadDirectory($path)');
        }

        $tmp = $this->files[$this->getName()]['tmp_name'];
        $destination = $this->getUploadDirectory() . DIRECTORY_SEPARATOR . $this->files[$this->getName()]['name'];

        return \move_uploaded_file($tmp, $destination);
    }
}
