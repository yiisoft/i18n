<?php

namespace Yii\I18n\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\I18n\Event\MissingTranslationEvent;
use Yiisoft\I18n\MessageFormatterInterface;
use Yiisoft\I18n\MessageReaderInterface;
use Yiisoft\I18n\Translator\Translator;

final class TranslatorTest extends TestCase
{
    /**
     * @dataProvider getTranslations
     * @param string|null $id
     * @param string|null $translation
     * @param string|null $expected
     * @param array $parameters
     * @param string|null $category
     */
    public function testTranslation(
        ?string $id,
        ?string $translation,
        ?string $expected,
        array $parameters,
        ?string $category
    ): void {
        $messageReader = $this->createMessageReader([$id => $translation]);

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

        $this->assertEquals($expected, $translator->translate($id, $parameters, $category));
    }

    public function testFallbackLocale(): void
    {
        $category = 'test';
        $message = 'test';
        $fallbackMessage = 'test de locale';

        $messageReader = $this->createMessageReader(['test' => $fallbackMessage]);

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
                    $this->createMessageReader([]),
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

    private function createMessageReader(array $messages): MessageReaderInterface
    {
        return new class ($messages) implements MessageReaderInterface {
            private array $messages;

            public function __construct(array $messages)
            {
                $this->messages = $messages;
            }

            public function all($context = null): array
            {
                return $this->messages;
            }

            public function one(string $id, $context = null): ?string
            {
                return $this->messages[$id] ?? null;
            }

            public function plural(string $id, int $count, $context = null): ?string
            {
                return $this->messages[$id] ?? null;
            }
        };
    }
}
