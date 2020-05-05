<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class ZoneSearchKeywordField implements FieldInterface
{
    protected const NAME = 'ZoneSearchKeyword';

    /**
     * @var string
     */
    private $keyword;

    public function __construct(string $keyword)
    {
        $this->keyword = $keyword;
    }

    public function isValid()
    {
        return true;
    }

    public function value()
    {
        return $this->keyword;
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
