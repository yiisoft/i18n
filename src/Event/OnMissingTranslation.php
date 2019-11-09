<?php

namespace Yiisoft\I18n\Event;

class OnMissingTranslation
{
    public function __construct(string $category, string $language, string $message)
    {
    }

    public function fallback(): array
    {
    }

    public function hasFallback(): bool
    {
    }
}