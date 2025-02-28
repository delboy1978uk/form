<?php

declare(strict_types=1);

namespace Del\Form\Traits;

use DOMDocument;
use DOMElement;
use DOMText;

trait HasDomTrait
{
    private DOMDocument $dom;

    public function createElement(string $tagType): DOMElement
    {
        return $this->dom->createElement($tagType);
    }

    public function createText($text): DOMText
    {
        return new DOMText($text ?? '');
    }

    public function createLineBreak(): DOMElement
    {
        return $this->createElement('br');
    }

    public function getDom(): DOMDocument
    {
        return $this->dom;
    }

    public function setDom($dom): void
    {
        $this->dom = $dom;
    }
}
