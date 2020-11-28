<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 23:36
 */

namespace Del\Form\Renderer\Error;


use Del\Form\Field\FieldInterface;
use DOMElement;
use DOMText;

class DefaultErrorRender extends AbstractErrorRender implements ErrorRendererInterface
{
    /**
     * @param FieldInterface $field
     * @return DOMElement
     */
    public function render(FieldInterface $field)
    {
        $helpBlock = $this->getDom()->createElement('span');
        $helpBlock->setAttribute('class', 'text-danger');

        if ($this->shouldRender($field)) {
            $helpBlock = $field->hasCustomErrorMessage()
                ? $this->addCustomErrorMessage($helpBlock, $field)
                : $this->addErrorMessages($helpBlock, $field);
        }
        return $helpBlock;
    }

    /**
     * @param DOMElement $helpBlock
     * @param FieldInterface $field
     * @return DOMElement
     */
    private function addCustomErrorMessage(DOMElement $helpBlock, FieldInterface $field)
    {
        $message = $field->getCustomErrorMessage();
        $text = $this->createText($message);
        $helpBlock->appendChild($text);
        return $helpBlock;
    }

    /**
     * @param DOMElement $helpBlock
     * @param FieldInterface $field
     * @return DOMElement
     */
    private function addErrorMessages(DOMElement $helpBlock, FieldInterface $field)
    {
        $messages = $field->getMessages();

        foreach ($messages as $message) {
            $helpBlock = $this->appendMessage($helpBlock, $message);
        }
        return $helpBlock;
    }

    /**
     * @param DOMElement $helpBlock
     * @param $message
     * @return DOMElement
     */
    private function appendMessage(DOMElement $helpBlock, $message)
    {
        $text = $this->createText($message);
        $br = $this->createLineBreak();
        $helpBlock->appendChild($text);
        $helpBlock->appendChild($br);
        return $helpBlock;
    }

}