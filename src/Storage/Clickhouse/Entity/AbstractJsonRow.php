<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity;

use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\FieldInterface;
use \RuntimeException;

/**
 * Class JsonRow
 *
 * @package Noobus\GrootLib\Storage\Clickhouse\Entity
 */
class AbstractJsonRow implements RowInterface
{
    protected const VALID_INDEX = [];

    /**
     * @var array
     */
    private $values = [];

    /**
     *
     */
    public function reset(): void
    {
        $this->values = [];
    }

    /**
     * @param string $index
     * @param $value
     * @throws \InvalidArgumentException
     */
    public function set(string $index, FieldInterface $field): void
    {
        if (!$this->hasField($index)) {
            throw new \InvalidArgumentException('Field ' . $index . ' not found in type ' . static::class);
        }
        if (!$field->isValid()) {
            throw new \InvalidArgumentException('Field ' . $index . ' has invalid value');
        }

        $this->values[$index] = $field->value();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $encoded = json_encode($this->values);
        if ($encoded === false) {
            throw new RuntimeException('Error encoding JsonRow');
        }
        return $encoded;
    }

    /**
     * @param string $index
     * @return bool
     */
    protected function hasField(string $index): bool
    {
        return in_array($index, self::VALID_INDEX);
    }
}
