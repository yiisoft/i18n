<?php
namespace Yii\I18n\Tests;

use Yii\I18n\Locale;
use PHPUnit\Framework\TestCase;

/**
 * @group i18n
 */
class LocaleTest extends Testcase
{
    public function testInvalidConstructorShouldThrowException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Locale('invalid-locale_zz-123');
    }

    public function testLanguageParsedCorrectly()
    {
        $locale = new Locale('en');
        $this->assertSame('en', $locale->language());
    }

    public function testRegionParsedCorrectly()
    {
        $locale = new Locale('fr-CA');
        $this->assertSame('fr', $locale->language());
        $this->assertSame('CA', $locale->region());
    }

    public function testScriptParsedCorrectly()
    {
        $locale = new Locale('zh-Hans');
        $this->assertSame('zh', $locale->language());
        $this->assertSame('Hans', $locale->script());
    }

    public function testVariantParsedCorrectly()
    {
        $locale = new Locale('de-DE-1901');
        $this->assertSame('de', $locale->language());
        $this->assertSame('DE', $locale->region());
        $this->assertSame('1901', $locale->variant());
    }

    public function testPrivateParsedCorrectly()
    {
        $locale = new Locale('x-fr-CH');
        $this->assertSame('fr-CH', $locale->private());
    }

    public function testAsString()
    {
        $localeString = 'en-GB-boont-r-extended-sequence-x-private';
        $locale = new Locale($localeString);

        $this->assertSame($localeString, $locale->asString());
    }

    public function testWithLanguage()
    {
        $locale = new Locale('ru-RU');
        $newLocale = $locale->withLanguage('en');

        $this->assertSame('ru', $locale->language());
        $this->assertSame('en', $newLocale->language());
    }

    public function testWithPrivate()
    {
        $locale = new Locale('en-GB-boont-x-private');
        $newLocale = $locale->withPrivate('newprivate');

        $this->assertSame('private', $locale->private());
        $this->assertSame('newprivate', $newLocale->private());
    }

    public function testFallback()
    {
        $locale = new Locale('en-GB-boont-x-private');

        $fallbackLocale = $locale->fallbackLocale();
        $this->assertSame('en-GB', $fallbackLocale->asString());

        $fallbackLocale = $fallbackLocale->fallbackLocale();
        $this->assertSame('en', $fallbackLocale->asString());
    }
}
