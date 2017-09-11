<?php
/**
 * User: delboy1978uk
 * Date: 01/01/2017
 * Time: 19:58
 */

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
    public function getTag()
    {
        return 'input';
    }

    public function init()
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
    private function hasUploadedFile()
    {
        return $this->isFileArraySet() && $this->isTempNameSet();
    }

    /**
     * @return bool
     */
    private function isFileArraySet()
    {
        return isset($this->files[$this->getName()]);
    }

    /**
     * @return bool
     */
    private function isTempNameSet()
    {
        return isset($this->files[$this->getName()]['tmp_name']);
    }

    /**
     * @param $path
     * @return $this
     */
    public function setUploadDirectory($path)
    {
        $path = realpath($path);
        if (!is_dir($path) || !is_writable($path)) {
            throw new InvalidArgumentException('Directory does not exist or is not writable.');
        }
        $this->uploadDirectory = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadDirectory()
    {
        return $this->uploadDirectory;
    }

    /**
     * @return bool
     */
    public function hasUploadDirectory()
    {
        return $this->uploadDirectory !== null;
    }

    /**
     * @return bool
     */
    public function moveUploadToDestination()
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