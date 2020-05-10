<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class ZoneCategoryIdField implements FieldInterface
{
    protected const NAME = 'ZoneCategoryId';

    /**
     * @var int
     */
    private $id;

    public function __construct(int $id = 0)
    {
        $this->id = $id;
    }

    public function isValid()
    {
        return $this->id >= 0;
    }

    public function value()
    {
        return $this->id;
    }

    public static function name(): string
    {
        return self::NAME;
    }

    public static function toSql(): string
    {
        return sprintf('`%s` UInt16', static::name());
    }
}
