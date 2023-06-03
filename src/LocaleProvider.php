<?php

declare(strict_types=1);

namespace Yiisoft\I18n;

/**
 * LocaleProvider is a stateful service that stores current locale.
 * Other services may use it as a dependency.
 */
final class LocaleProvider
{
    private Locale $locale;
    private Locale $defaultLocale;

    /**
     * @param Locale $defaultLocale
     */
    public function __construct(Locale $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function get(): Locale
    {
        return $this->locale ?? $this->defaultLocale;
    }

    public function getDefaultLocale(): Locale
    {
        return $this->defaultLocale;
    }

    public function set(Locale $locale): void
    {
        $this->locale = $locale;
    }
}
