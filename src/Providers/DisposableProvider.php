<?php

namespace Auvernhat\EasyEmailValidator\Providers;

class DisposableProvider extends ProviderAbstract
{
    private const DENY_DOMAINS_URL = 'https://raw.githubusercontent.com/disposable/disposable-email-domains/master/domains.txt';

    /**
     * @return string[]
     */
    public function getAllowDomains(): array
    {
        return [];
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
     * Fetches content from a URL and returns an array of trimmed, non-empty lines.
     *
     * @param string $url
     * @return array<mixed>|null
     */
    private function getContents(string $url): ?array
    {
        $domainsString = @file_get_contents($url);
        if ($domainsString === false) {
            return null;
        }

        $domainsArray = explode("\n", $domainsString);
        $domainsArray = array_map('trim', $domainsArray);
        $domainsArray = array_filter($domainsArray);

        return array_values($domainsArray);
    }
}
