<?php


namespace Yii\I18N;


interface MessageWriter
{
    public function write(array $messages): void;
}