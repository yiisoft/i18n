<?php

declare(strict_types=1);

namespace Yiisoft\I18n;

interface MessageFormatterInterface
{
    /**
     * @param string $message
     * @param array $parameters
     * @param string $language
     * @return string
     */
    public function format(string $message, array $parameters, string $language): string;
}
