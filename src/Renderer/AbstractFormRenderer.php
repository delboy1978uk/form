<?php
/**
 * User: delboy1978uk
 * Date: 07/12/2016
 * Time: 01:54
 */

namespace Del\Form\Renderer;

use Del\Form\Collection\FieldCollection;
use Del\Form\AbstractForm;
use Del\Form\Field\FieldInterface;
use Del\Form\FormInterface;
use Del\Form\Renderer\Error\DefaultErrorRender;
use Del\Form\Renderer\Error\ErrorRendererInterface;
use Del\Form\Traits\HasDomTrait;
use DOMDocument;
use DOMElement;

abstract class AbstractFormRenderer implements FormRendererInterface
{
    use HasDomTrait;

    /** @var DOMElement $form */
    protected $form;

    /** @var bool $displayErrors */
    protected $displayErrors;

    /** @var ErrorRendererInterface $errorRenderer */
    protected $errorRenderer;

    /** @var DOMElement $label The label element */
    protected $label;

    /** @var DOMElement $element the field element */
    protected $element;

    /** @var null|\DOMElement $errors The error block html */
    protected $errors;

    /** @var DOMElement $block The containing html block */
    protected $block;

    /** @var DOMElement $dynamicContainerBlock */
    protected $dynamicContainerBlock;

    /** @var FieldInterface $field The current field being processed */
    protected $field;

    /** @var bool $includeDynamicFormJavascript */
    private $includeDynamicFormJavascript = false;

    /** @var string $dynamicFormParentName */
    private $dynamicFormParentName = '';

    /** @var bool $dynamicFormVisible */
    private $dynamicFormVisible = false;

    public function __construct()
    {
        $this->resetDom();
    }

    /**
     *  resets dom
     */
    private function resetDom()
    {
        $this->setDom(new DOMDocument());
        $this->form = $this->getDom()->createElement('form');
        $this->errorRenderer = new DefaultErrorRender($this->dom);
    }

    /**
     * @param FormInterface $form
     * @param bool $displayErrors
     * @return string
     */
    public function render(FormInterface $form, $displayErrors = true)
    {
        $this->displayErrors = $displayErrors;
        $this->setFormAttributes($form);

        $fields = $form->getFields();
        $this->processFields($fields);

        $this->getDom()->appendChild($this->form);
        $html = $this->getDom()->saveHTML();
        $this->resetDom();

        $html .= $this->addDynamicFormJavascript();
        return $html;
    }

    /**
     * @param FormInterface $form
     */
    private function setFormAttributes(FormInterface $form)
    {
        $attributes = $form->getAttributes();
        foreach ($attributes as $key => $value) {
            $this->form->setAttribute($key, $value);
        }

        // set Id as name or method as post if not set
        $method = $this->getMethod($form);
        $id = $this->getId($form);

        $this->form->setAttribute('id', $id);
        $this->form->setAttribute('method', $method);
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    private function getMethod(FormInterface $form)
    {
        return $form->getMethod() ?: AbstractForm::METHOD_POST;
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    private function getId(FormInterface $form)
    {
        return $form->getId() ?: $this->form->getAttribute('name');
    }

    /**
     * @param FieldCollection $fields
     * @param string $dynamicTriggerValue
     */
    private function processFields(FieldCollection $fields, $dynamicTriggerValue = null)
    {
        $count = $fields->count();
        $x = 1;
        $fields->rewind();
        while ($fields->valid()) {
            $this->field = $fields->current();
            $finaliseDynamicBlock = ($x == $count) ? true : false;
            $this->renderField($dynamicTriggerValue, $finaliseDynamicBlock);
            $x++;
            $fields->next();
        }
        $fields->rewind();
    }

    /**
     * @param string $dynamicTriggerValue
     * @param bool $finaliseDynamicBlock
     */
    public function renderField($dynamicTriggerValue = null, $finaliseDynamicBlock = false)
    {
        $this->createNewDynamicContainerBlockIfNeeded($dynamicTriggerValue);

        $this->block = $this->createElement('div');
        $this->label = $this->renderFieldLabel();
        $this->element = $this->field->getRenderer()->render($this->dom, $this->field);
        $this->errors = $this->field->isValid() ? null : $this->renderError();
        $this->block = $this->renderFieldBlock();

        is_null($dynamicTriggerValue)
            ? $this->form->appendChild($this->block)
            : $this->dynamicContainerBlock->appendChild($this->block);

        $this->dynamicFormCheck();
        $this->finaliseDynamicBlockIfNeeded($finaliseDynamicBlock);
    }

    /**
     * This creates a containing div for dynamic fields which appear only on another fields value
     * @param null|string $dynamicTriggerValue
     */
    private function createNewDynamicContainerBlockIfNeeded($dynamicTriggerValue)
    {
        if (!isset($this->dynamicContainerBlock) && $dynamicTriggerValue !== null) {
            $this->dynamicContainerBlock = $this->createElement('div');
            $this->dynamicContainerBlock->setAttribute('data-dynamic-form', $this->dynamicFormParentName);
            $this->dynamicContainerBlock->setAttribute('data-dynamic-form-trigger-value', $dynamicTriggerValue);
            $this->dynamicContainerBlock->setAttribute('class', 'dynamic-form-block trigger'.$this->dynamicFormParentName);
            $this->dynamicContainerBlock->setAttribute('id', $this->dynamicFormParentName.$dynamicTriggerValue);
            $this->dynamicFormVisible === false ? $this->dynamicContainerBlock->setAttribute('style', 'display: none;') : null;
        }
    }

    /**
     *  Checks current field being processed for dynamic sub forms
     */
    private function dynamicFormCheck()
    {
        if ($this->field->hasDynamicForms()) {
            $this->dynamicFormParentName = $this->field->getName();
            $value = $this->field->getValue();
            $forms = $this->field->getDynamicForms();
            $this->includeDynamicFormJavascript = true;
            foreach ($forms as $dynamicTriggerValue => $form) {
                $this->dynamicFormVisible = ($value == $dynamicTriggerValue);
                $dynamicFields = $form->getFields();
                $this->processFields($dynamicFields, $dynamicTriggerValue);
            }
            unset($this->dynamicFormParentName);
        }
    }

    /**
     * @param bool $finaliseDynamicBlock
     */
    private function finaliseDynamicBlockIfNeeded($finaliseDynamicBlock)
    {
        if (isset($this->dynamicContainerBlock) && $finaliseDynamicBlock === true) {
            $this->form->appendChild($this->dynamicContainerBlock);
            unset($this->dynamicContainerBlock);
        }
    }


    /**
     * @return DOMElement|null
     */
    public function renderError()
    {
        $errorBlock = null;
        if ($this->errorRenderer->shouldRender($this->field) && $this->displayErrors === true) {
            $this->block->setAttribute('class', 'has-error ');
            $errorBlock = $this->errorRenderer->render($this->field);
        }
        return $errorBlock;
    }

    /**
     * @return \DOMElement
     */
    protected function createLabelElement()
    {
        $label = $this->createElement('label');
        $label->setAttribute('for', $this->field->getId());
        if ($this->field->isRequired()) {
            $label = $this->addRequiredAsterisk($label);
        }
        return $label;
    }

    /**
     * @param DomElement $label
     * @return DomElement
     */
    public function addRequiredAsterisk(DomElement $label)
    {
        $span = $this->createElement('span');
        $span->setAttribute('class', 'text-danger');
        $text = $this->createText('* ');
        $span->appendChild($text);
        $label->appendChild($span);
        return $label;
    }

    /**
     * @return string
     */
    private function addDynamicFormJavascript()
    {
        if ($this->includeDynamicFormJavascript === true) {
            return "<script type=\"text/javascript\">
                $(document).ready(function(){
                    $('.dynamic-form-block').each(function(){
                        var Id = $(this).prop('id');
                        var parentField = $(this).attr('data-dynamic-form');
                        var parentValue = $(this).attr('data-dynamic-form-trigger-value');
            
                        $('input[name=\"'+parentField+'\"]').change(function(){
                            var val = $(this).val();
                            if (val == parentValue) {
                                $('.trigger'+parentField).each(function(){
                                    $(this).attr('style', 'display: none;');
                                });
                                $('#'+Id).attr('style', 'display: block;');
                            }
                        });
                    });
                });
            </script>
            ";
        }
        return '';
    }
}