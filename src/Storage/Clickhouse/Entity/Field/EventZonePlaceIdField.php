<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class EventZonePlaceIdField implements FieldInterface
{
    protected const NAME = 'EventZonePlaceId';

    /**
     * @var int
     */
    private $id = 0;

    public function __construct(string $id = '0')
    {
        $this->id = intval($id);
    }

    public function isValid()
    {
        return is_numeric($this->id);
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
        return sprintf('`%s` UInt32', static::name());
    }
}
