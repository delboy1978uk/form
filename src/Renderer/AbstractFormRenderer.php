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

    public function __construct($name)
    {
        $this->dom = new DOMDocument();
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
            /** @todo $this these vars, move field abstract logic in here or extending class */
            $current = $fields->current();
            $label = $this->renderLabel();
            $element = $current->getRenderer()->render($this->dom, $current, $this->displayErrors);
            $errors = $current->isValid() ? $this->renderError($element) : null;
            $contents = $this->renderEntireBlock();
            $this->form->appendChild($contents);
            $fields->next();
        }
        $fields->rewind();
    }

    abstract public function renderEntireBlock();
    abstract public function renderError();
    abstract public function renderLabel();
    abstract public function renderField();
}