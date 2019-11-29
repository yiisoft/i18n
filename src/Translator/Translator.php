<?php

namespace Yiisoft\I18n\Translator;

use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\I18n\Event\MissingTranslationEvent;
use Yiisoft\I18n\Locale;
use Yiisoft\I18n\MessageReaderInterface;
use Yiisoft\I18n\TranslatorInterface;

class Translator implements TranslatorInterface
{
    /**
     * @var \Yiisoft\I18n\MessageReaderInterface
     */
    private $messageReader;
    /**
     * @var \Psr\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var array
     */
    private $messages = [];

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        MessageReaderInterface $messageReader
    ) {
        $this->messageReader = $messageReader;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Translates a message to the specified language.
     * If a translation is not found, a {{@see \Yiisoft\I18n\Event\MissingTranslationEvent} event will be triggered.
     *
     * @param string $message the message to be translated
     * @param string $category the message category
     * @param string $localeString the target locale
     * @return string|null the translated message or false if translation wasn't found or isn't required
     */
    public function translate(?string $message, string $category = null, string $localeString = null): ?string
    {
        if ($localeString === null) {
            $localeString = $this->getDefaultLocale();
        }

        if ($category === null) {
            $category = $this->getDefaultCategory();
        }

        $messages = $this->getMessages($category, $localeString);

        if (array_key_exists($message, $messages)) {
            return $messages[$message];
        }

        $missingTranslation = new MissingTranslationEvent($category, $localeString, $message);
        $this->eventDispatcher->dispatch($missingTranslation);

        $locale = new Locale($localeString);
        $fallback = $locale->fallbackLocale();

        if ($fallback->asString() !== $locale->asString()) {
            return $messages[$message] = $this->translate($message, $category, $fallback->asString());
        }

        return $messages[$message] = $message;
    }

    private function getMessages(string $category, string $language): array
    {
        $key = $language . '/' . $category;

        if (!array_key_exists($key, $this->messages)) {
            $this->messages[$key] = $this->messageReader->all($key);
        }

        return $this->messages[$key];
    }

    protected function getDefaultCategory(): string
    {
        return 'default';
    }

    protected function getDefaultLocale(): string
    {
        return \Locale::getDefault();
    }
}
