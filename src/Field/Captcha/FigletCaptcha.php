<?php declare(strict_types=1);

namespace Del\Form\Field\Captcha;

use DateTime;
use Del\Form\Validator\ValidatorInterface;
use Del\SessionManager;
use Laminas\Text\Figlet\Figlet;

class FigletCaptcha implements CaptchaAdapterInterface, ValidatorInterface
{
    /** @var SessionManager $session */
    private $session;

    /** @var string $timeout */
    private $timeout;

    /** @var string $timeout */
    private $length;

    /** @var string $word */
    private $word;

    /** @var array $errors */
    private $errors = [];

    public function __construct(SessionManager $session, string $timeout = '+3 minutes', int $length = 6)
    {
        $this->session = $session;
        $this->timeout = $timeout;
        $this->length = $length;
        $this->generate();
    }

    /**
     * @return string
     */
    public function generate(): string
    {
        $expiry = new DateTime();
        $session = $this->session->get('captcha');

        if ($session && $session['expiry'] > $expiry) {
            $this->word = $session['word'];
        } else {
            $this->word = $this->generateWord();
            $expiry->modify($this->timeout);

            $this->session->set('captcha', [
                'word' => $this->word,
                'expiry' => $expiry,
            ]);
        }

        return $this->word;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $word = $this->word;
        $figlet = new Figlet();

        return '<div class="mono">' . ($figlet->render($word)) . '</div>';
    }

    /**
     * @return string
     */
    private function generateWord(): string
    {
        $word = '';

         for ($x = 0; $x < $this->length; $x ++) {
             $word .= chr(rand(97, 122));
         }

        return $word;
    }

    /**
     * @param  mixed $value
     * @return bool
     * @throws Exception If validation of $value is impossible
     */
    public function isValid($value)
    {
        $now = new DateTime();
        $captcha = $this->session->get('captcha');
        $expiry = $captcha['expiry'];
        $word = $captcha['word'];

        if ($now > $expiry) {
            $this->errors = ['The CAPTCHA has expired'];

            return false;
        }

        if (!is_null($value) && $value !== $word) {
            $this->errors = ['The CAPTCHA value did not match.'];

            return false;
        }

        if (!is_null($value)) {
            $this->session->destroy('captcha');
        }

        return true;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->errors;
    }
}
