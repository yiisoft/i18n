<?php


namespace Yii\I18N;


interface Formatter
{
    public function format(string $message, array $parameters): string;
}
