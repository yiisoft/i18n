<?php

declare(strict_types=1);

namespace Yiisoft\I18n;

interface MessageWriterInterface
{
    public function write(array $messages): void;
}
