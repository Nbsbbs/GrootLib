<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Event;

class EventType
{
    public const TYPE_VIEW = 'view';
    public const TYPE_CLICK = 'click';
    public const TYPE_BOUNCE = 'bounce';
    public const TYPE_COMPLAINT = 'complaint';
    public const TYPE_FLASH = 'flash'; // показывает, что элемент существует
    public const TYPE_DELETE = 'delete'; // показывает, что элемент удален

    public const VALID_TYPES = [
        self::TYPE_VIEW,
        self::TYPE_CLICK,
        self::TYPE_BOUNCE,
        self::TYPE_COMPLAINT,
        self::TYPE_FLASH,
        self::TYPE_DELETE,
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
