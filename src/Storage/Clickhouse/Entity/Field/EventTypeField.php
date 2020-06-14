<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

use Noobus\GrootLib\Entity\Event\EventType;

/**
 * Class EventTypeField
 *
 * @package Noobus\GrootLib\Storage\Clickhouse\Entity\Field
 */
class EventTypeField implements FieldInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     *
     */
    protected const ENUM_TYPES = [
        1 => EventType::TYPE_VIEW,
        2 => EventType::TYPE_CLICK,
        3 => EventType::TYPE_BOUNCE,
        4 => EventType::TYPE_COMPLAINT,
        5 => EventType::TYPE_FLASH,
    ];
    /**
     *
     */
    protected const NAME = 'EventType';

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
        return EventType::isValidType($this->type);
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
