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

    public function testLanguageIsNormalizedCorrectly(): void
    {
        $locale = new Locale('EN');
        $this->assertSame('en', $locale->language());
    }

    public function testRegionParsedCorrectly(): void
    {
        $locale = new Locale('fr-CA');
        $this->assertSame('CA', $locale->region());
    }

    public function testRegionIsNormalizedCorrectly(): void
    {
        $locale = new Locale('fr-ca');
        $this->assertSame('CA', $locale->region());
    }

    public function testScriptParsedCorrectly(): void
    {
        $locale = new Locale('zh-Hans');
        $this->assertSame('Hans', $locale->script());
    }

    public function testScriptIsNormalizedCorrectly(): void
    {
        $locale = new Locale('zh-hANS');
        $this->assertSame('Hans', $locale->script());
    }

    public function testVariantParsedCorrectly(): void
    {
        $locale = new Locale('de-DE-1901');
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

    public function testColcasefirstParsedCorrectly(): void
    {
        $locale = new Locale('fr-Latn-FR@colcasefirst=upper');
        $this->assertSame('upper', $locale->colcasefirst());
    }

    public function testCollationParsedCorrectly(): void
    {
        $locale = new Locale('es@collation=traditional');
        $this->assertSame('traditional', $locale->collation());
    }

    public function testColnumericParsedCorrectly(): void
    {
        $locale = new Locale('fr-Latn-FR@colnumeric=yes');
        $this->assertSame('yes', $locale->colnumeric());
    }

    public function testNumbersParsedCorrectly(): void
    {
        $locale = new Locale('ru-RU@numbers=latn');
        $this->assertSame('latn', $locale->numbers());
    }

    public function testHoursParsedCorrectly(): void
    {
        $locale = new Locale('fr-FR@hours=h23');
        $this->assertSame('h23', $locale->hours());
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
        $localeString = 'zh-cmn-Hans-CN-boont-r-extended-sequence-x-private@'
            . 'currency=USD;colcasefirst=lower;collation=traditional;colnumeric=no;'
            . 'calendar=buddhist;numbers=latn;hours=h24';
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

    public function testWithExtendedLanguage(): void
    {
        $locale = new Locale('zh-CN');
        $newLocale = $locale->withExtendedLanguage('cmn');

        $this->assertNull($locale->extendedLanguage());
        $this->assertSame('cmn', $newLocale->extendedLanguage());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithCurrency(): void
    {
        $locale = new Locale('uk-UA');
        $newLocale = $locale->withCurrency('USD');

        $this->assertNull($locale->currency());
        $this->assertSame('USD', $newLocale->currency());
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

    public function testWithRegion(): void
    {
        $locale = new Locale('fr');
        $newLocale = $locale->withRegion('CA');

        $this->assertNull($locale->region());
        $this->assertSame('CA', $newLocale->region());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithNumbers(): void
    {
        $locale = new Locale('fr');
        $newLocale = $locale->withNumbers('latn');

        $this->assertNull($locale->numbers());
        $this->assertSame('latn', $newLocale->numbers());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithHours(): void
    {
        $locale = new Locale('fr');
        $newLocale = $locale->withHours('h12');

        $this->assertNull($locale->hours());
        $this->assertSame('h12', $newLocale->hours());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithColcasefirst(): void
    {
        $locale = new Locale('fr');
        $newLocale = $locale->withColcasefirst('false');

        $this->assertNull($locale->colcasefirst());
        $this->assertSame('false', $newLocale->colcasefirst());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithCollation(): void
    {
        $locale = new Locale('fr');
        $newLocale = $locale->withCollation('traditional');

        $this->assertNull($locale->collation());
        $this->assertSame('traditional', $newLocale->collation());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithColnumeric(): void
    {
        $locale = new Locale('fr');
        $newLocale = $locale->withColnumeric('no');

        $this->assertNull($locale->colnumeric());
        $this->assertSame('no', $newLocale->colnumeric());
        $this->assertNotSame($locale, $newLocale);
    }

    public function testWithVariant(): void
    {
        $locale = new Locale('de-DE');
        $newLocale = $locale->withVariant('1901');

        $this->assertNull($locale->variant());
        $this->assertSame('1901', $newLocale->variant());
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
