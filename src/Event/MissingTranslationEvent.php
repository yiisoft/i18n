<?php

namespace Yiisoft\I18n\Event;

class MissingTranslationEvent
{
    public function __construct(string $category, string $language, string $message)
    {
    }

    public function fallback(): ?string
    {
        return null;
    }

    public function hasFallback(): bool
    {
        return true;
    }
}