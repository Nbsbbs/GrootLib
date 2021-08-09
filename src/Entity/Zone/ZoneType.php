<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

class ZoneType
{
    public const TYPE_CATEGORY = 'category';
    public const TYPE_TITLE = 'title';
    public const TYPE_SEARCH = 'search';
    public const TYPE_RELATED = 'related';
    public const TYPE_EMBED = 'embed';
    public const TYPE_MIXED = 'mixed';
    public const TYPE_QUERY_TREE = 'query_tree';
    public const TYPE_FIXED_SEARCH_QUERY = 'fixed_search_query';
    public const TYPE_FIXED_TOP_QUERY = 'fixed_top_query';
    public const VALID_TYPES = [
        self::TYPE_CATEGORY,
        self::TYPE_TITLE,
        self::TYPE_SEARCH,
        self::TYPE_RELATED,
        self::TYPE_MIXED,
        self::TYPE_QUERY_TREE,
        self::TYPE_FIXED_SEARCH_QUERY,
        self::TYPE_FIXED_TOP_QUERY,
        self::TYPE_EMBED,
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
