<?php

namespace Yiisoft\I18n;

interface TranslationsLoaderInterface
{
    public function load($category, $language): array;
}