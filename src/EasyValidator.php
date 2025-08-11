<?php

namespace Auvernhat\EasyEmailValidator;

use Auvernhat\EasyEmailValidator\Providers\ProviderAbstract;
use Auvernhat\EasyEmailValidator\Providers\DisposableProvider;

class EasyValidator
{
    /**
     * @var string[]
     */
    private array $allowDomains = [];
    /**
     * @var string[]
     */
    private array $denyDomains = [];
    /**
     * @var ProviderAbstract
     */
    private ProviderAbstract $provider;

    public function __construct(?ProviderAbstract $provider = null)
    {
        $this->provider = $provider ?? new DisposableProvider();
        $this->loadDomains();
    }

    private function loadDomains(): void
    {
        $this->allowDomains = array_unique($this->provider->getAllowDomains());
        $this->denyDomains = array_unique($this->provider->getDenyDomains());
    }

    /**
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool
    {
        $parts = explode("@", $email);
        if (count($parts) !== 2) {
            return false;
        }
        $emailDomain = $parts[1];

        if (in_array($emailDomain, $this->allowDomains, true)) {
            return true;
        }
        if (in_array($emailDomain, $this->denyDomains, true)) {
            return false;
        }
        return $this->allowDomains === [];
    }

    /**
     * @param string[] $emails
     * @return bool
     */
    public function validateMultiple(array $emails): bool
    {
        foreach ($emails as $email) {
            if (!$this->validate($email)) {
                return false;
            }
        }
        return true;
    }
}
