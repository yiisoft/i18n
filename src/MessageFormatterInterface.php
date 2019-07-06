<?php
namespace Yiisoft\I18n;

interface MessageFormatterInterface
{
    /**
     * @throws FormattingException
     */
    public function format(string $message, array $parameters, string $language): string;
}
