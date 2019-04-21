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
        $this->assertSame('en', $locale->getLanguage());
    }

    public function testRegionParsedCorrectly()
    {
        $locale = new Locale('fr-CA');
        $this->assertSame('fr', $locale->getLanguage());
        $this->assertSame('CA', $locale->getRegion());
    }

    public function testScriptParsedCorrectly()
    {
        $locale = new Locale('zh-Hans');
        $this->assertSame('zh', $locale->getLanguage());
        $this->assertSame('Hans', $locale->getScript());
    }

    public function testVariantParsedCorrectly()
    {
        $locale = new Locale('de-DE-1901');
        $this->assertSame('de', $locale->getLanguage());
        $this->assertSame('DE', $locale->getRegion());
        $this->assertSame('1901', $locale->getVariant());
    }

    public function testPrivateParsedCorrectly()
    {
        $locale = new Locale('x-fr-CH');
        $this->assertSame('fr-CH', $locale->getPrivate());
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

        $this->assertSame('ru', $locale->getLanguage());
        $this->assertSame('en', $newLocale->getLanguage());
    }

    public function testWithPrivate()
    {
        $locale = new Locale('en-GB-boont-x-private');
        $newLocale = $locale->withPrivate('newprivate');

        $this->assertSame('private', $locale->getPrivate());
        $this->assertSame('newprivate', $newLocale->getPrivate());
    }

    public function testFallback()
    {
        $locale = new Locale('en-GB-boont-x-private');
        $fallbackLocale = $locale->getFallbackLocale();
        $this->assertSame('en-GB', $fallbackLocale->asString());

        $fallbackLocale = $fallbackLocale->getFallbackLocale();
        $this->assertSame('en', $fallbackLocale->asString());
    }
}
