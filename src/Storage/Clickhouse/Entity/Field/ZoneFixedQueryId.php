<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class ZoneFixedQueryId implements FieldInterface
{
    protected const NAME = 'ZoneFixedQueryId';

    /**
     * @var int
     */
    private int $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function isValid()
    {
        return true;
    }

    public function value()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public static function name(): string
    {
        return self::NAME;
    }

    /**
     * @return string
     */
    public static function toSql(): string
    {
        return sprintf('`%s` UInt32', static::name());
    }
}
