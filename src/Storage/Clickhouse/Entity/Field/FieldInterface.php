<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

interface FieldInterface
{
    public function isValid();
    public function value();
    public static function name(): string;
    public static function toSql(): string;
}
