<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

/**
 * Class EventTypeField
 *
 * @package Noobus\GrootLib\Storage\Clickhouse\Entity\Field
 */
class UserIp4Field implements FieldInterface
{
    /**
     * @var string
     */
    private $ip;

    /**
     *
     */
    protected const NAME = 'UserIp4';

    /**
     * EventTypeField constructor.
     *
     * @param string $type
     */
    public function __construct(string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->ip;
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
        return sprintf('`%s` IPv4', static::name());
    }
}
