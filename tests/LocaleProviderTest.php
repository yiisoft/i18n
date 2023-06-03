<?php

declare(strict_types=1);

namespace Yiisoft\I18n\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\I18n\Locale;
use Yiisoft\I18n\LocaleProvider;

final class LocaleProviderTest extends TestCase
{
    public function testDefaultLocale(): void
    {
        $defaultLocale = new Locale('en');

        $localeProvider = new LocaleProvider($defaultLocale);
        $this->assertSame($defaultLocale, $localeProvider->get());
        $this->assertSame($defaultLocale, $localeProvider->getDefaultLocale());
    }

    public function testSetLocale(): void
    {
        $defaultLocale = new Locale('en');
        $locale = new Locale('de');

        $localeProvider = new LocaleProvider($defaultLocale);
        $localeProvider->set($locale);

        $this->assertEquals($locale, $localeProvider->get());
    }
}
