<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

class ZoneType
{
    public const TYPE_CATEGORY = 'category';
    public const TYPE_TITLE = 'title';
    public const TYPE_SEARCH = 'search';
    public const TYPE_RELATED = 'related';
    public const TYPE_MIXED = 'mixed';

    public const VALID_TYPES = [
        self::TYPE_CATEGORY,
        self::TYPE_TITLE,
        self::TYPE_SEARCH,
        self::TYPE_RELATED,
        self::TYPE_MIXED,
    ];

    /**
     * @param string $type
     * @return bool
     */
    public static function isValidType(string $type): bool
    {
        return in_array($type, self::VALID_TYPES);
    }
}
