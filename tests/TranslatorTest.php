<?php

namespace Yii\I18n\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\I18n\MessageFormatterInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\I18n\Event\MissingTranslationEvent;
use Yiisoft\I18n\MessageReaderInterface;
use Yiisoft\I18n\Translator\Translator;

class TranslatorTest extends TestCase
{
    /**
     * @dataProvider getTranslations
     * @param $message
     * @param $translate
     * @param $expected
     * @param $parameters
     * @param $category
     */
    public function testTranslation($message, $translate, $expected, $parameters, $category)
    {
        $messageReader = $this->getMockBuilder(MessageReaderInterface::class)
            ->getMock();

        $messageFormatter = null;
        if ([] !== $parameters) {
            $messageFormatter = $this->getMockBuilder(MessageFormatterInterface::class)
                ->getMock();
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

        $messageReader->expects($this->once())
            ->method('all')
            ->willReturn([$message => $translate]);

        if ($messageFormatter instanceof MessageFormatterInterface) {
            $messageFormatter->expects($this->once())
                ->method('format')
                ->willReturn($this->formatMessage($translate, $parameters));
        }

        $this->assertEquals($expected, $translator->translate($message, $parameters, $category));
    }

    public function testFallbackLocale()
    {
        $category = 'test';
        $message = 'test';
        $fallbackMessage = 'test de locale';

        $messageReader = $this->getMockBuilder(MessageReaderInterface::class)
            ->getMock();

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

        $messageReader
            ->method('all')
            ->will($this->onConsecutiveCalls([], ['test' => $fallbackMessage]));

        $this->assertEquals($fallbackMessage, $translator->translate($message, [], $category, 'en'));
    }

    public function testMissingEventTriggered()
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
