<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

/**
 * Class SessionIdField
 *
 * @package Noobus\GrootLib\Storage\Clickhouse\Entity\Field
 */
class UserSessionIdField implements FieldInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     *
     */
    protected const NAME = 'UserSessionId';

    /**
     * EventTypeField constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $this->id) > 0;
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
