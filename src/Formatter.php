<?php


namespace Yii\I18n;


interface Formatter
{
    public function format(string $message, array $parameters, string $language): string;
}
