<?php declare(strict_types=1);

namespace Del\Form\Renderer\Field;

use Del\Form\Field\Captcha;
use Del\Form\Field\FieldInterface;
use DOMDocument;
use DOMElement;

class CaptchaRender extends AbstractFieldRender implements FieldRendererInterface
{
    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMElement
     */
    public function renderBlock(FieldInterface $field, DOMElement $element): DOMElement
    {
        if (!$field instanceof Captcha) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\Captcha');
        }

        $dom = $this->getDom();
        $captcha = $dom->createDocumentFragment();
        $captcha->appendXML($field->getCaptchAdapter()->render());

        $div = $this->getDom()->createElement('div');
        $div->appendChild($captcha);
        $div->appendChild($element);

        return $div;
    }
}
