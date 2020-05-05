<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

/**
 * Class UserUserAgentField
 *
 * @package Noobus\GrootLib\Storage\Clickhouse\Entity\Field
 */
class UserUserAgentField implements FieldInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     *
     */
    protected const NAME = 'UserUserAgent';

    /**
     * EventTypeField constructor.
     *
     * @param string $userAgent
     */
    public function __construct(string $userAgent)
    {
        $this->id = $userAgent;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return true;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->id;
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
