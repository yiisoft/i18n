<?php


namespace Yiisoft\I18n;


interface Resource extends MessageReader, MessageWriter
{
    public function sourceLanguage(): ?string;
    public function targetLanguage(): string;
}