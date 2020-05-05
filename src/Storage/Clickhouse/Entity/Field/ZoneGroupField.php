<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class ZoneGroupField implements FieldInterface
{
    protected const NAME = 'ZoneGroup';

    /**
     * @var string
     */
    private $group;

    public function __construct(string $group)
    {
        $this->group = $group;
    }

    public function isValid()
    {
        return true;
    }

    public function value()
    {
        return $this->group;
    }

    public static function name(): string
    {
        return self::NAME;
    }

    public static function toSql(): string
    {
        return sprintf('`%s` String', static::name());
    }
}
