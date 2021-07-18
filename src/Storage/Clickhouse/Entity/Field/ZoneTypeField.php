<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

use Noobus\GrootLib\Entity\Zone\ZoneType;

/**
 * Class ZoneTypeField
 *
 * @package Noobus\GrootLib\Storage\Clickhouse\Entity\Field
 */
class ZoneTypeField implements FieldInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     *
     */
    protected const ENUM_TYPES = [
        1 => ZoneType::TYPE_CATEGORY,
        2 => ZoneType::TYPE_TITLE,
        3 => ZoneType::TYPE_SEARCH,
        4 => ZoneType::TYPE_RELATED,
        5 => ZoneType::TYPE_MIXED,
        6 => ZoneType::TYPE_QUERY_TREE,
        7 => ZoneType::TYPE_FIXED_SEARCH_QUERY,
        8 => ZoneType::TYPE_FIXED_TOP_QUERY,
    ];
    /**
     *
     */
    protected const NAME = 'ZoneType';

    /**
     * EventTypeField constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = strtolower($type);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ZoneType::isValidType($this->type);
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->type;
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
        return sprintf('`%s` Enum(%s)', static::name(), self::sqlExportPossibleValues());
    }

    /**
     * @return string
     */
    protected static function sqlExportPossibleValues(): string
    {
        $collection = [];
        foreach (self::ENUM_TYPES as $index => $valueName) {
            $collection[] = "'" . $valueName . "' = " . $index;
        }

        return implode(', ', $collection);
    }
}
