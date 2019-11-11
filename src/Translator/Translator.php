<?php

namespace Yiisoft\I18n\Translator;

use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\I18n\Event\MissingTranslationEvent;
use Yiisoft\I18n\TranslatorInterface;

class Translator implements TranslatorInterface
{
    /**
     * @var \Yiisoft\I18n\Translator\TranslationsLoaderInterface
     */
    private $translationsLoader;
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
        TranslationsLoaderInterface $translationsLoader
    ) {
        $this->translationsLoader = $translationsLoader;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Translates a message to the specified language.
     * If a translation is not found, a {{@see \Yiisoft\I18n\Event\MissingTranslationEvent} event will be triggered.
     * @param string $message the message to be translated
     * @param string $category the message category
     * @param string $locale the target locale
     * @return string|null the translated message or false if translation wasn't found or isn't required
     */
    public function translate(?string $message, string $category = null, string $locale = null): ?string
    {
        if ($locale === null) {
            $locale = $this->getDefaultLocale();
        }

        if ($category === null) {
            $category = $this->getDefaultCategory();
        }

        $messages = $this->getMessages($category, $locale);

        if (array_key_exists($message, $messages)) {
            return $messages[$message];
        }

        $missingTranslation = new MissingTranslationEvent($category, $locale, $message);
        $this->eventDispatcher->dispatch($missingTranslation);

        if ($missingTranslation->hasFallback()) {
            return $messages[$message] = $missingTranslation->fallback();
        }

        return $messages[$message] = null;
    }

    private function getMessages(string $category, string $language): array
    {
        $key = $language . '/' . $category;

        if (!array_key_exists($key, $this->messages)) {
            $this->messages[$key] = $this->translationsLoader->load($category, $language);
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
