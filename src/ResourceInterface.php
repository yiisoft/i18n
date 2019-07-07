<?php
namespace Yiisoft\I18n;

interface ResourceInterface extends MessageReaderInterface, MessageWriterInterace
{
    public function sourceLanguage(): ?string;
    public function targetLanguage(): string;
}
