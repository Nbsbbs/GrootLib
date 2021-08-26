<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class EventUrlField implements FieldInterface
{
    /**
     * @var string
     */
    private string $eventUrl;

    /**
     *
     */
    protected const NAME = 'EventUrl';

    /**
     * EventTypeField constructor.
     *
     * @param string $eventUrl
     */
    public function __construct(string $eventUrl = '')
    {
        $this->eventUrl = $eventUrl;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if ($url = filter_var($this->eventUrl, FILTER_VALIDATE_URL)) {
            return true;
        }
        if (empty($url)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->eventUrl;
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
        return sprintf('`%s` String', static::name());
    }
}
