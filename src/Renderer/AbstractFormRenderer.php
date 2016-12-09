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
use DOMDocument;
use DomElement;

abstract class AbstractFormRenderer implements FormRendererInterface
{
    /** @var DOMDocument $dom */
    protected $dom;

    /** @var DomElement $form */
    protected $form;

    /** @var bool $displayErrors */
    protected $displayErrors;

    /** @var ErrorRendererInterface $errorRenderer */
    protected $errorRenderer;

    /** @var DomElement $label The label element*/
    protected $label;

    /** @var DomElement $element the field element */
    protected $element;

    /** @var DomElement $errors The error block html*/
    protected $errors;

    /** @var DomElement $block The containing html block */
    protected $block;

    /** @var FieldInterface $field The current field being processed */
    protected $field;

    public function __construct($name)
    {
        $this->dom = new DOMDocument();
        $this->errorRenderer = new DefaultErrorRender($this->dom);
        $form = $this->dom->createElement('form');
        $form->setAttribute('name', $name);
        $this->form = $form;
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

        $this->dom->appendChild($this->form);
        return $this->dom->saveHTML();
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

    private function processFields(FieldCollection $fields)
    {
        $fields->rewind();
        while ($fields->valid()) {
            $this->block = $this->dom->createElement('div');
            $this->field = $fields->current();
            $this->label = $this->renderFieldLabel();
            $this->element = $this->field->getRenderer()->render($this->dom, $this->field);
            $this->errors = $this->field->isValid() ? null : $this->renderError();
            $this->block = $this->renderFieldBlock();
            $this->form->appendChild($this->block);
            $fields->next();
        }
        $fields->rewind();
    }



    /**
     * @return DOMElement|null
     */
    public function renderError()
    {
        $errorBlock = null;
        if (!$this->field->isValid() && $this->displayErrors === true) {
            $this->block->setAttribute('class', 'has-error ');
            $errorBlock = $this->errorRenderer->render($this->field);
        }
        return $errorBlock;
    }
}