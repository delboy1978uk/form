<?php
/**
 * User: delboy1978uk
 * Date: 29/11/2016
 * Time: 19:44
 */

namespace Del\Form\Renderer;

use Del\Form\Collection\FieldCollection;
use Del\Form\AbstractForm;
use Del\Form\FormInterface;
use DOMDocument;
use DOMElement;

class FormRenderer
{
    /** @var DOMDocument $dom */
    private $dom;

    /** @var DomElement $form */
    private $form;

    /** @var bool $displayErrors */
    private $displayErrors;

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
        $method = $this->getMethod($form);
        $id = $this->getId($form);
        $action = $this->getAction($form);
        $encType = $this->getEncType($form);
        $class = $form->getClass();

        $this->form->setAttribute('id', $id);
        $this->form->setAttribute('method', $method);
        $this->form->setAttribute('class', $class);
        $this->form->setAttribute('action', $action);
        $this->form->setAttribute('enctype', $encType);
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
     * @param FormInterface $form
     * @return string
     */
    private function getAction(FormInterface $form)
    {
        return $form->getAction() ?: $this->form->getAttribute('action');
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    private function getEncType(FormInterface $form)
    {
        return $form->getEncType() ?: $this->form->getAttribute('enc-type');
    }

    private function processFields(FieldCollection $fields)
    {
        $fields->rewind();
        while ($fields->valid()) {
            $current = $fields->current();
            $child = $current->getRenderer()->render($this->dom, $current, $this->displayErrors);
            $this->form->appendChild($child);
            $fields->next();
        }
        $fields->rewind();
    }

}