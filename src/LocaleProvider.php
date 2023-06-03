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

    public function get(): Locale
    {
        return $this->locale;
    }

    public function set(Locale $locale): void
    {
        $this->locale = $locale;
    }
}
