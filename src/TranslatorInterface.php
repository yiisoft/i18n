<?php

declare(strict_types=1);

namespace Yiisoft\I18n;

interface TranslatorInterface
{
    /**
     * Translates a message to the specified language.
     * If a translation is not found, a {{@see \Yiisoft\I18n\Event\MissingTranslationEvent} event will be triggered.
     *
     * @param string $message the message to be translated
     * @param array  $parameters An array of parameters for the message
     * @param string $category the message category
     * @param string $locale the target locale
     * @return string|null the translated message or false if translation wasn't found or isn't required
     */
    public function translate(
        ?string $message,
        array $parameters = [],
        string $category = null,
        string $locale = null
    ): ?string;
}
