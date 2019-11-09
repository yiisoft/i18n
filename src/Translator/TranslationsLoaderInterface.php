<?php

namespace Yiisoft\I18n\Translator;

interface TranslationsLoaderInterface
{
    public function load($category, $language): array;
}