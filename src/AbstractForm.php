<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 12:13
 */

namespace Del\Form;

use Del\Form\Collection\FieldCollection;
use Del\Form\Field\FieldInterface;
use Del\Form\Field\FileUpload;
use Del\Form\Renderer\FormRenderer;
use Del\Form\Renderer\FormRendererInterface;
use Del\Form\Traits\HasAttributesTrait;

abstract class AbstractForm implements FormInterface
{
    const ENC_TYPE_MULTIPART_FORM_DATA = 'multipart/form-data';
    const ENC_TYPE_URL_ENCODED = 'application/x-www-form-urlencoded';
    const ENC_TYPE_TEXT_PLAIN = 'text/plain';

    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    /** @var FieldCollection $fieldCollection */
    private $fieldCollection;

    /** @var FormRendererInterface  */
    private $formRenderer;

    /** @var array $errorMessages */
    private $errorMessages;

    /** @var bool $displayErrors */
    private $displayErrors;

    use HasAttributesTrait;

    /**
     * AbstractForm constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->fieldCollection = new FieldCollection();
        $this->formRenderer = new FormRenderer();
        $this->attributes = [
            'name' => $name,
            'method' => self::METHOD_POST,
        ];
        $this->displayErrors = false;
        $this->init();
    }

    abstract public function init();

    /**
     * @return bool
     */
    public function isValid()
    {
        $this->errorMessages = [];
        $fields = $this->fieldCollection;
        $this->validateFields($fields);
        $count = count($this->errorMessages);
        $valid = ($count == 0);
        if ($valid) {
            $this->moveUploadedFiles();
        }
        return $valid;
    }

    /**
     * @param FieldCollection $fields
     */
    private function validateFields(FieldCollection $fields)
    {
        $fields->rewind();
        while ($fields->valid()) {
            $this->checkFieldForErrors($fields->current());
            $this->checkDynamicFormsForErrors($fields->current());
            $fields->next();
        }
        $fields->rewind();
    }

    /**
     * @param FieldInterface $field
     */
    private function checkFieldForErrors(FieldInterface $field)
    {
        if (!$field->isValid()) {
            $this->errorMessages[$field->getName()] = $field->getMessages();
        }
    }

    public function checkDynamicFormsForErrors(FieldInterface $field)
    {
        if ($field->hasDynamicForms()) {
            $forms = $field->getDynamicForms();
            $value = $field->getValue();
            if (isset($forms[$value])) {
                $form = $forms[$value];
                $fields = $form->getFields();
                $this->validateFields($fields);
            }
        }
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = [];
        $fields = $this->fieldCollection;
        $values = $this->getFieldValues($fields, $values);
        return $values;
    }

    /**
     * @param FieldCollection $fields
     * @param array $values
     * @return array
     */
    private function getFieldValues(FieldCollection $fields, array $values)
    {
        $fields->rewind();
        while ($fields->valid()) {
            /** @var FieldInterface $field */
            $field = $fields->current();
            $value = $field->getValue();
            $values[$field->getName()] = $value;
            if ($field->hasDynamicForms()) {
                $forms = $field->getDynamicForms();
                if (isset($forms[$value])) {
                    $form = $forms[$value];
                    $dynamicFormFields = $form->getFields();
                    $values = $this->getFieldValues($dynamicFormFields, $values);
                }
            }
            $fields->next();
        }
        $fields->rewind();
        return $values;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function populate(array $data)
    {
        $fields = $this->fieldCollection;
        $this->populateFields($fields, $data);
        $this->displayErrors = true;
        return $this;
    }

    /**
     * @param array $dynamicForms
     * @param array $data
     */
    private function populateDynamicForms(array $dynamicForms, array $data)
    {
        /** @var FormInterface $form **/
        foreach ($dynamicForms as $form) {
            $fields = $form->getFields();
            $this->populateFields($fields, $data);
        }
    }

    /**
     * @param FieldCollection $fields
     * @param array $data
     */
    private function populateFields(FieldCollection $fields, array $data)
    {
        $fields->rewind();
        while ($fields->valid()) {
            $field = $fields->current();
            $this->populateField($field, $data);
            $fields->next();
        }
        $fields->rewind();
    }

    /**
     * @param FieldInterface $field
     * @param array $data
     */
    private function populateField(FieldInterface $field, array $data)
    {
        $name = $field->getName();
        if (isset($data[$name])) {
            $field->setValue($data[$name]);
        }
        if ($field->hasDynamicForms()) {
            $forms = $field->getDynamicForms();
            $this->populateDynamicForms($forms, $data);
        }
    }

    /**
     * @param string $name
     * @return FieldInterface|null
     */
    public function getField($name)
    {
        return $this->fieldCollection->findByName($name);
    }

    /**
     * @return FieldCollection
     */
    public function getFields()
    {
        return $this->fieldCollection;
    }

    /**
     * @param FieldInterface $field
     * @return $this
     */
    public function addField(FieldInterface $field)
    {
        $this->fieldCollection->append($field);
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->formRenderer->render($this, $this->isDisplayErrors());
    }

    /**
     * @param $url
     * @return $this
     */
    public function setAction($url)
    {
        $this->setAttribute('action', $url);
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->getAttribute('action');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->setAttribute('id', $id);
        return $this;
    }

    /**
     * @param $encType
     * @return $this
     */
    public function setEncType($encType)
    {
        $this->setAttribute('enctype', $encType);
        return $this;
    }

    /**
     * @return string
     */
    public function getEncType()
    {
        return $this->getAttribute('enctype');
    }

    /**
     * @param string $method
     * @return FormInterface
     */
    public function setMethod($method)
    {
        $this->setAttribute('method', $method);
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->getAttribute('method');
    }

    /**
     * @param $class
     * @return FormInterface
     */
    public function setClass($class)
    {
        $this->setAttribute('class', $class);
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->getAttribute('class');
    }

    /**
     * @return boolean
     */
    public function isDisplayErrors()
    {
        return $this->displayErrors;
    }

    /**
     * @param boolean $displayErrors
     * @return AbstractForm
     */
    public function setDisplayErrors($displayErrors)
    {
        $this->displayErrors = $displayErrors;
        return $this;
    }

    /**
     * @param FormRendererInterface $renderer
     * @return AbstractForm
     */
    public function setFormRenderer(FormRendererInterface $renderer)
    {
        $this->formRenderer = $renderer;
        return $this;
    }

    public function moveUploadedFiles()
    {
        $this->fieldCollection->rewind();
        while ($this->fieldCollection->valid()) {
            $current = $this->fieldCollection->current();
            $this->moveFileIfUploadField($current);
            $this->fieldCollection->next();
        }
    }

    /**
     * @param FieldInterface $field
     * @return bool
     */
    public function moveFileIfUploadField(FieldInterface $field)
    {
        if ($field instanceof FileUpload) {
            $field->moveUploadToDestination();
            return true;
        }
        return false;
    }
}