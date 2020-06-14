<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity;

interface TableInterface
{
    public static function getName(): string;
    public static function getBufferName(): string;
}
