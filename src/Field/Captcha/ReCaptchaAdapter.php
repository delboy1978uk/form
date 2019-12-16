<?php declare(strict_types=1);

namespace Del\Form\Field\Captcha;

use Del\Form\Validator\ValidatorInterface;
use Exception;

class ReCaptchaAdapter implements CaptchaAdapterInterface, ValidatorInterface
{
    /** @var string $siteKey */
    private $siteKey;

    /** @var string $secretKey */
    private $secretKey;

    /**
     * ReCaptchaAdapter constructor.
     * @param string $siteKey
     * @param string $secretKey
     */
    public function __construct(string $siteKey, string $secretKey)
    {
        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
    }

    public function generate(): string
    {

    }

    public function render(): string
    {
        return '<script src="https://www.google.com/recaptcha/api.js?render=' . $this->siteKey . '"></script>
        <script>
            $(document).ready(function(){
                $(\'#captcha\').parent().parent().parent().hide();
                grecaptcha.ready(function() {
                grecaptcha.execute(\'' . $this->siteKey . '\', {action: \'homepage\'}).then(function(token) {
                    $(\'#captcha\').val(token);
                });
                });
            });   
        </script>';
    }

    public function isValid($value)
    {
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $this->secretKey,
            'response' => $value,
        ]));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($verify);
        $data = json_decode($json, true);

        return $data['success'];
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return [];
    }

}