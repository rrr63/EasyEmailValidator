<?php

namespace Auv\EasyEmailValidator\Providers;

class AmieiroProvider extends ProviderAbstract
{
    private const ALLOW_DOMAINS_URL = 'https://raw.githubusercontent.com/amieiro/disposable-email-domains/master/allowDomains.json';
    private const DENY_DOMAINS_URL = 'https://raw.githubusercontent.com/amieiro/disposable-email-domains/master/denyDomains.json';

    /**
     * @return string[]
     */
    public function getAllowDomains(): array
    {
        $data = $this->getContents(self::ALLOW_DOMAINS_URL);
        return is_array($data) ? array_values(array_filter($data, 'is_string')) : [];
    }

    /**
     * @return string[]
     */
    public function getDenyDomains(): array
    {
        $data = $this->getContents(self::DENY_DOMAINS_URL);
        return is_array($data) ? array_values(array_filter($data, 'is_string')) : [];
    }

    /**
     * @param string $url
     * @return array<mixed>|null
     */
    private function getContents(string $url): ?array
    {
        $json = @file_get_contents($url);
        if ($json === false) {
            return null;
        }
        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }
}
