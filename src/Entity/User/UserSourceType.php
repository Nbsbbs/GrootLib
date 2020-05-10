<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\User;

class UserSourceType
{
    public const TYPE_UNKNOWN = 'unknown'; // from unknown site
    public const TYPE_SEARCH = 'search'; // from search engines
    public const TYPE_BOOKMARK = 'bookmark'; // must have cookie and no referer
    public const TYPE_NOREF = 'noref'; // no referer, no cookie
    public const TYPE_FEED = 'feed'; // no referer, no cookie
    public const TYPE_TRADE = 'trade'; // from a list of trade domains

    public const INTERNAL = 'internal'; // from our site, but no session

    public const VALID_TYPES = [
        self::TYPE_UNKNOWN,
        self::TYPE_SEARCH,
        self::TYPE_FEED,
        self::TYPE_BOOKMARK,
        self::TYPE_TRADE,
        self::TYPE_NOREF,
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
