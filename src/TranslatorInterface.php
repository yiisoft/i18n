<?php

namespace Yiisoft\I18n;

interface TranslatorInterface
{
    public function translate(?string $message, string $category = null, string $locale = null): ?string;
}
