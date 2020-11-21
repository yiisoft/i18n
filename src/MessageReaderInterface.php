<?php

declare(strict_types=1);

namespace Yiisoft\I18n;

interface MessageReaderInterface
{
    public function all($context = null): array;

    public function one(string $id, $context = null): ?string;

    public function plural(string $id, int $count, $context = null): ?string;
}
