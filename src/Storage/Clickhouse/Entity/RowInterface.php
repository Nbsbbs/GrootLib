<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity;

use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\FieldInterface;

interface RowInterface
{
    public function reset(): void;

    /**
     * @param string $index
     * @param $field
     */
    public function set(string $index, FieldInterface $field): void;

    /**
     * @return string
     */
    public function toString(): string;
}
