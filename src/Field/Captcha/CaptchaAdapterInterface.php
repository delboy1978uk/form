<?php declare(strict_types=1);

namespace Del\Form\Field\Captcha;

interface CaptchaAdapterInterface
{
    /**
     * @return string
     */
    public function generate(): string;

    public function render(): string;
}