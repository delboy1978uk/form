<?php
/**
 * User: delboy1978uk
 * Date: 27/12/2016
 * Time: 00:27
 */

namespace Del\Form\Traits;

use DOMDocument;
use DOMElement;
use DOMText;

trait HasDomTrait
{
    /** @var DOMDocument $dom  */
    private $dom;

    /**
     * @param $tagType
     * @return DOMElement
     */
    public function createElement($tagType)
    {
        return $this->dom->createElement($tagType);
    }

    /**
     * @param $text
     * @return DOMText
     */
    public function createText($text)
    {
        return new DOMText($text);
    }

    /**
     * @return DOMElement
     */
    public function createLineBreak()
    {
        return $this->createElement('br');
    }

    /**
     * @return DOMDocument
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param DOMDocument $dom
     * @return $this
     */
    public function setDom($dom)
    {
        $this->dom = $dom;
        return $this;
    }
}