<?php

namespace Yiisoft\I18n;

interface MessageWriterInterface
{
    public function write(array $messages): void;
}
