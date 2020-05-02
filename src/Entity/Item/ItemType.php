<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Item;

class ItemType
{
    public const TYPE_THUMB = 'thumb';
    public const TYPE_VIDEO = 'video';
    public const TYPE_CATEGORY = 'category';

    public const VALID_TYPES = [
        self::TYPE_THUMB,
        self::TYPE_VIDEO,
        self::TYPE_CATEGORY,
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
