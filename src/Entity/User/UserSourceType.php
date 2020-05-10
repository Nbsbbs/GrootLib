<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\User;

class UserSourceType
{
    public const TYPE_UNKNOWN = 'unknown';
    public const TYPE_SEARCH = 'se';
    public const TYPE_FEED = 'feed';
    public const TYPE_BOOKMARK = 'bookmark';
    public const TYPE_BOT = 'bot';

    public const VALID_TYPES = [
        self::TYPE_UNKNOWN,
        self::TYPE_SEARCH,
        self::TYPE_FEED,
        self::TYPE_BOOKMARK,
        self::TYPE_BOT,
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
