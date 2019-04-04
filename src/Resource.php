<?php


namespace Yii\I18N;


interface Resource extends MessageReader, MessageWriter
{
    public function sourceLanguage(): ?string;
    public function targetLanguage(): string;
}