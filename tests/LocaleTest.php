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

    public function testLanguageParsedIsLowercased(): void
    {
        $locale = new Locale('EN');
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

    public function testCalendarParsedCorrectly(): void
    {
        $locale = new Locale('ru-RU@calendar=buddhist');
        $this->assertSame('buddhist', $locale->calendar());
    }

    public function testCollationParsedCorrectly(): void
    {
        $locale = new Locale('es@collation=traditional');
        $this->assertSame('traditional', $locale->collation());
    }

    public function testNumbersParsedCorrectly(): void
    {
        $locale = new Locale('ru-RU@numbers=latn');
        $this->assertSame('latn', $locale->numbers());
    }

    public function testCurrencyParsedCorrectly(): void
    {
        $locale = new Locale('ru-RU@currency=USD');
        $this->assertSame('USD', $locale->currency());
    }

    public function testExtendedLanguageParsedCorrectly(): void
    {
        $locale = new Locale('zh-cmn-Hans-CN');
        $this->assertSame('cmn', $locale->extendedLanguage());
    }

    public function testPrivateIsParsedCorrectly(): void
    {
        $locale = new Locale('en-GB-boont-x-private');
        $this->assertSame('private', $locale->private());
    }

    public function testAsString(): void
    {
        $localeString = 'zh-cmn-Hans-CN-boont-r-extended-sequence-x-private@currency=USD;collation=traditional;calendar=buddhist;numbers=latn';
        $localeStringGrandFathered = 'zh-xiang';
        $locale = new Locale($localeString);
        $localeGrandFathered = new Locale($localeStringGrandFathered);

        $this->assertSame($localeString, $locale->asString());
        $this->assertSame($localeString, (string)$locale);

        $this->assertSame($localeStringGrandFathered, $localeGrandFathered->asString());
        $this->assertSame($localeStringGrandFathered, (string)$localeGrandFathered);

    }

    public function testWithLanguage(): void
    {
        $locale = new Locale('ru-RU');
        $newLocale = $locale->withLanguage('en');

        $this->assertSame('ru', $locale->language());
        $this->assertSame('en', $newLocale->language());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithPrivate(): void
    {
        $locale = new Locale('en-GB-boont-x-private');
        $newLocale = $locale->withPrivate('newprivate');

        $this->assertSame('private', $locale->private());
        $this->assertSame('newprivate', $newLocale->private());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithScript(): void
    {
        $locale = new Locale('zh');
        $newLocale = $locale->withScript('Hans');

        $this->assertNull($locale->script());
        $this->assertSame('Hans', $newLocale->script());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithCalendar(): void
    {
        $locale = new Locale('ru-RU');
        $newLocale = $locale->withCalendar('buddhist');

        $this->assertNull($locale->calendar());
        $this->assertSame('buddhist', $newLocale->calendar());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testFallback(): void
    {
        $locale = new Locale('en-GB-boont-x-private');

        $fallbackLocale1 = $locale->fallbackLocale();
        $this->assertSame('en-GB', $fallbackLocale1->asString());
        $this->assertNotSame($locale, $fallbackLocale1);

        $fallbackLocale2 = $fallbackLocale1->fallbackLocale();
        $this->assertSame('en', $fallbackLocale2->asString());
        $this->assertNotSame($fallbackLocale1, $fallbackLocale2);

        $fallbackLocale3 = $fallbackLocale2->fallbackLocale();
        $this->assertSame('en', $fallbackLocale3->asString());
        $this->assertNotSame($fallbackLocale2, $fallbackLocale3);
    }

    public function testFallbackScript(): void
    {
        $locale = new Locale('zh-Hans');
        $fallbackLocale = $locale->fallbackLocale();
        $this->assertSame('zh', $fallbackLocale->asString());
        $this->assertNotSame($locale, $fallbackLocale);
    }
}
