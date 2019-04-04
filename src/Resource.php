<?php


namespace Yii\I18n;


interface Resource extends MessageReader, MessageWriter
{
    public function sourceLanguage(): ?string;
    public function targetLanguage(): string;
}