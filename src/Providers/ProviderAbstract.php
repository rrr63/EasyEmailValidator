<?php

namespace R63\EasyEmailValidator\Providers;

abstract class ProviderAbstract
{
    /**
     * Get the list of allowed domains.
     *
     * @return string[]
     */
    abstract public function getAllowDomains(): array;

    /**
     * Get the list of denied domains.
     *
     * @return string[]
     */
    abstract public function getDenyDomains(): array;
}
