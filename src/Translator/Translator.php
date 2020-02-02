<?php

declare(strict_types=1);

namespace Yiisoft\I18n\Translator;

use Yiisoft\I18n\MessageFormatterInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\I18n\Event\MissingTranslationEvent;
use Yiisoft\I18n\Locale;
use Yiisoft\I18n\MessageReaderInterface;
use Yiisoft\I18n\TranslatorInterface;

class Translator implements TranslatorInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private MessageReaderInterface $messageReader;
    private ?MessageFormatterInterface $messageFormatter;
    private ?string $locale = null;
    private ?string $defaultLocale = null;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        MessageReaderInterface $messageReader,
        MessageFormatterInterface $messageFormatter = null
    ) {
        $this->messageReader = $messageReader;
        $this->eventDispatcher = $eventDispatcher;
        $this->messageFormatter = $messageFormatter;
    }

    /**
     * Sets the current locale.
     *
     * @param string $locale The locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * Returns the current locale.
     *
     * @return string The locale
     */
    public function getLocale(): string
    {
        return $this->locale ?? $this->getDefaultLocale();
    }

    public function setDefaultLocale(string $locale): void
    {
        $this->defaultLocale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function translate(
        string $id,
        array $parameters = [],
        string $category = null,
        string $localeString = null
    ): ?string {
        if ($localeString === null) {
            $localeString = $this->getLocale();
        }

        if ($category === null) {
            $category = $this->getDefaultCategory();
        }

        $message = $this->messageReader->one($id, $localeString . '/' . $category);
        if ($message === null) {
            $missingTranslation = new MissingTranslationEvent($category, $localeString, $id);
            $this->eventDispatcher->dispatch($missingTranslation);

            $locale = new Locale($localeString);
            $fallback = $locale->fallbackLocale();

            if ($fallback->asString() !== $locale->asString()) {
                return $this->translate($id, $parameters, $category, $fallback->asString());
            }

            $defaultFallback = (new Locale($this->getDefaultLocale()))->fallbackLocale();

            if ($defaultFallback->asString() !== $fallback->asString()) {
                return $this->translate($id, $parameters, $category, $this->getDefaultLocale());
            }

            $message = $id;
        }

        if ($this->messageFormatter === null) {
            return $message;
        }

        return $this->messageFormatter->format($message, $parameters, $localeString);
    }

    protected function getDefaultCategory(): string
    {
        return 'default';
    }

    protected function getDefaultLocale(): string
    {
        return $this->defaultLocale ?? \Locale::getDefault();
    }
}
