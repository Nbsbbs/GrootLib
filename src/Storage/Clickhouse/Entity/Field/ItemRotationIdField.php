<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class ItemRotationIdField implements FieldInterface
{
    protected const NAME = 'ItemRotationId';

    /**
     * @var string
     */
    private string $id;

    public function __construct(string $id = '')
    {
        $this->id = $id;
    }

    public function isValid()
    {
        return preg_match('#^[\w\d_-]$#s', $this->id);
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
        return sprintf('`%s` String', static::name());
    }
}
