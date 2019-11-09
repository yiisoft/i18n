<?php

namespace Yiisoft\I18n;

interface TranslationsLoaderInterface
{
    public function loadMessages($category, $language): array;
}