<?php

declare(strict_types=1);

namespace Del\Form\Renderer\Error;

use Del\Form\Field\FieldInterface;
use DOMElement;

class HorizontalFormErrorRender extends AbstractErrorRender
{
    public function render(FieldInterface $field): DOMElement
    {
        $helpBlock = $this->createElement('span');
        $helpBlock->setAttribute('class', 'text-danger');

        if ($this->shouldRender($field)) {
            $helpBlock = $field->hasCustomErrorMessage()
                ? $this->addCustomErrorMessage($helpBlock, $field)
                : $this->addErrorMessages($helpBlock, $field);
        }

        $div = $this->createElement('div');
        $div->setAttribute('class', 'col-sm-offset-2 col-sm-10');
        $div->appendChild($helpBlock);
        return $div;
    }

    private function addCustomErrorMessage(DOMElement $helpBlock, FieldInterface $field): DOMElement
    {
        $message = $field->getCustomErrorMessage();
        $text = $this->createText($message);
        $helpBlock->appendChild($text);

        return $helpBlock;
    }

    private function addErrorMessages(DOMElement $helpBlock, FieldInterface $field): DOMElement
    {
        $messages = $field->getMessages();

        foreach ($messages as $message) {
            $helpBlock = $this->appendMessage($helpBlock, $message);
        }
        return $helpBlock;
    }

    private function appendMessage(DOMElement $helpBlock, string $message): DOMElement
    {
        $text = $this->createText($message);
        $br = $this->createLineBreak();
        $helpBlock->appendChild($text);
        $helpBlock->appendChild($br);
        return $helpBlock;
    }
}
