<?php

namespace Del\Form\Field;

use Del\Form\Field\Captcha\CaptchaAdapterInterface;
use Del\Form\Filter\Adapter\FilterAdapterZf;
use Del\Form\Renderer\Field\CaptchaRender;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

class Captcha extends Text
{
    /** @var CaptchaAdapterInterface $captchAdapter */
    private $captchaAdapter;

    public function init(): void
    {
        parent::init();
        $this->setRenderer(new CaptchaRender());
    }

    /**
     * @param CaptchaAdapterInterface $captchaAdapter
     */
    public function setCaptchaAdapter(CaptchaAdapterInterface $captchaAdapter): void
    {
        $this->captchaAdapter = $captchaAdapter;
    }

    /**
     * @return CaptchaAdapterInterface
     */
    public function getCaptchAdapter(): CaptchaAdapterInterface
    {
        return $this->captchaAdapter;
    }
}