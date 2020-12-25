<?php

declare(strict_types=1);

namespace Yii\I18n\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\I18n\Locale;

/**
 * @group i18n
 */
final class LocaleTest extends Testcase
{
    public function testInvalidConstructorShouldThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Locale('invalid-locale_zz-123');
    }

    public function testLanguageParsedCorrectly(): void
    {
        $locale = new Locale('en');
        $this->assertSame('en', $locale->language());
    }

    public function testRegionParsedCorrectly(): void
    {
        $locale = new Locale('fr-CA');
        $this->assertSame('fr', $locale->language());
        $this->assertSame('CA', $locale->region());
    }

    public function testScriptParsedCorrectly(): void
    {
        $locale = new Locale('zh-Hans');
        $this->assertSame('zh', $locale->language());
        $this->assertSame('Hans', $locale->script());
    }

    public function testVariantParsedCorrectly(): void
    {
        $locale = new Locale('de-DE-1901');
        $this->assertSame('de', $locale->language());
        $this->assertSame('DE', $locale->region());
        $this->assertSame('1901', $locale->variant());
    }

    public function testPrivateParsedCorrectly(): void
    {
        $locale = new Locale('x-fr-CH');
        $this->assertSame('fr-CH', $locale->private());
    }

    public function testAsString(): void
    {
        $localeString = 'en-GB-boont-r-extended-sequence-x-private';
        $locale = new Locale($localeString);

        $this->assertSame($localeString, $locale->asString());
    }

    public function testWithLanguage(): void
    {
        $locale = new Locale('ru-RU');
        $newLocale = $locale->withLanguage('en');

        $this->assertSame('ru', $locale->language());
        $this->assertSame('en', $newLocale->language());
    }

    public function testWithPrivate(): void
    {
        $locale = new Locale('en-GB-boont-x-private');
        $newLocale = $locale->withPrivate('newprivate');

        $this->assertSame('private', $locale->private());
        $this->assertSame('newprivate', $newLocale->private());
    }

    public function testFallback(): void
    {
        $locale = new Locale('en-GB-boont-x-private');

        $fallbackLocale = $locale->fallbackLocale();
        $this->assertSame('en-GB', $fallbackLocale->asString());

        $fallbackLocale = $fallbackLocale->fallbackLocale();
        $this->assertSame('en', $fallbackLocale->asString());
    }
}
