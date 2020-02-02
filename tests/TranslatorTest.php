<?php

namespace Yii\I18n\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\I18n\MessageFormatterInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\I18n\Event\MissingTranslationEvent;
use Yiisoft\I18n\MessageReaderInterface;
use Yiisoft\I18n\Translator\Translator;

final class TranslatorTest extends TestCase
{
    /**
     * @dataProvider getTranslations
     * @param string|null $message
     * @param string|null $translation
     * @param string|null $expected
     * @param array $parameters
     * @param string|null $category
     */
    public function testTranslation(?string $message, ?string $translation, ?string $expected, array $parameters, ?string $category): void
    {
        $messageReader = $this->getMockBuilder(MessageReaderInterface::class)->getMock();
        $messageReader
            ->method('all')
            ->willReturn([$message => $translation]);

        $messageFormatter = null;
        if ([] !== $parameters) {
            $messageFormatter = $this->getMockBuilder(MessageFormatterInterface::class)->getMock();
            $messageFormatter
                ->method('format')
                ->willReturn($this->formatMessage($translation, $parameters));
        }

        /**
         * @var $translator Translator
         */
        $translator = $this->getMockBuilder(Translator::class)
            ->setConstructorArgs(
                [
                    $this->createMock(EventDispatcherInterface::class),
                    $messageReader,
                    $messageFormatter
                ]
            )
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $this->assertEquals($expected, $translator->translate($message, $parameters, $category));
    }

    public function testFallbackLocale(): void
    {
        $category = 'test';
        $message = 'test';
        $fallbackMessage = 'test de locale';

        $messageReader = $this->getMockBuilder(MessageReaderInterface::class)->getMock();
        $messageReader
            ->method('all')
            ->will($this->onConsecutiveCalls([], ['test' => $fallbackMessage]));

        /**
         * @var $translator Translator
         */
        $translator = $this->getMockBuilder(Translator::class)
            ->setConstructorArgs(
                [
                    $this->createMock(EventDispatcherInterface::class),
                    $messageReader,
                ]
            )
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $translator->setDefaultLocale('de');



        $this->assertEquals($fallbackMessage, $translator->translate($message, [], $category, 'en'));
    }

    public function testMissingEventTriggered(): void
    {
        $category = 'test';
        $language = 'en';
        $message = 'Message';

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->setMethods(['dispatch'])
            ->getMock();

        /**
         * @var $translator Translator
         */
        $translator = $this->getMockBuilder(Translator::class)
            ->setConstructorArgs(
                [
                    $eventDispatcher,
                    $this->createMock(MessageReaderInterface::class),
                ]
            )
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $translator->setDefaultLocale('de');

        $eventDispatcher
            ->expects($this->at(0))
            ->method('dispatch')
            ->with(new MissingTranslationEvent($category, $language, $message));

        $translator->translate($message, [], $category, $language);
    }

    public function getTranslations(): array
    {
        return [
            [null, null, null, [], null],
            ['test', 'test', 'test', [], null],
            ['test {param}', 'translated {param}', 'translated param-value', ['param' => 'param-value'], null],
        ];
    }

    private function formatMessage(string $message, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;
    }
}
