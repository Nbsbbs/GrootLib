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
     * @param string|null $ip
     */
    public function __construct(?string $ip = null)
    {
        $this->ip = $ip;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (is_null($this->ip)) {
            return true;
        }

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
        if (!empty($this->ip)) {
            return $this->ip;
        } else {
            return '0.0.0.0';
        }
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
        return sprintf('`%s` Nullable(IPv4)', static::name());
    }
}
