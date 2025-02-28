<?php

declare(strict_types=1);

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

    protected DOMElement $form;
    protected bool $displayErrors;
    protected ErrorRendererInterface $errorRenderer;
    protected DOMElement $label;
    protected DOMElement $element;
    protected ?DOMElement $errors = null;
    protected DOMElement $block;
    protected DOMElement $dynamicContainerBlock;
    protected FieldInterface $field;
    private bool $includeDynamicFormJavascript = false;
    private string $dynamicFormParentName = '';
    private bool $dynamicFormVisible = false;

    public function __construct()
    {
        $this->resetDom();
    }

    private function resetDom(): void
    {
        $this->setDom(new DOMDocument());
        $this->form = $this->getDom()->createElement('form');
        $this->errorRenderer = new DefaultErrorRender($this->dom);
    }

    public function render(FormInterface $form, $displayErrors = true): string
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

    private function setFormAttributes(FormInterface $form): void
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

    private function getMethod(FormInterface $form): string
    {
        return $form->getMethod() ?: AbstractForm::METHOD_POST;
    }

    private function getId(FormInterface $form): string
    {
        return $form->getId() ?: $this->form->getAttribute('name');
    }

    private function processFields(FieldCollection $fields, $dynamicTriggerValue = null): string
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

    public function renderField($dynamicTriggerValue = null, $finaliseDynamicBlock = false): void
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

    private function createNewDynamicContainerBlockIfNeeded($dynamicTriggerValue): void
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
    private function dynamicFormCheck(): void
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

    private function finaliseDynamicBlockIfNeeded(bool $finaliseDynamicBlock)
    {
        if (isset($this->dynamicContainerBlock) && $finaliseDynamicBlock === true) {
            $this->form->appendChild($this->dynamicContainerBlock);
            unset($this->dynamicContainerBlock);
        }
    }

    public function renderError(): ?DOMElement
    {
        $errorBlock = null;

        if ($this->errorRenderer->shouldRender($this->field) && $this->displayErrors === true) {
            $this->block->setAttribute('class', 'has-error ');
            $errorBlock = $this->errorRenderer->render($this->field);
        }

        return $errorBlock;
    }

    protected function createLabelElement(): DOMElement
    {
        $label = $this->createElement('label');
        $label->setAttribute('for', $this->field->getId() ?? '');
        if ($this->field->isRequired()) {
            $label = $this->addRequiredAsterisk($label);
        }
        return $label;
    }

    public function addRequiredAsterisk(DomElement $label): DomElement
    {
        $span = $this->createElement('span');
        $span->setAttribute('class', 'text-danger');
        $text = $this->createText('* ');
        $span->appendChild($text);
        $label->appendChild($span);
        return $label;
    }

    private function addDynamicFormJavascript(): string
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
