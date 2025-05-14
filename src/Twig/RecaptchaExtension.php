<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RecaptchaExtension extends AbstractExtension
{
    private $recaptchaSiteKey;
    private $recaptchaSecretKey;

    public function __construct(string $recaptchaSiteKey, string $recaptchaSecretKey)
    {
        $this->recaptchaSiteKey = $recaptchaSiteKey;
        $this->recaptchaSecretKey = $recaptchaSecretKey;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('RECAPTCHA_SITE_KEY', [$this, 'getSiteKey']),
            new TwigFunction('RECAPTCHA_SECRET_KEY', [$this, 'getSecretKey']),
        ];
    }

    public function getSiteKey(): string
    {
        return $this->recaptchaSiteKey;
    }

    public function getSecretKey(): string
    {
        return $this->recaptchaSecretKey;
    }
}
