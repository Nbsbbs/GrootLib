<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

use Noobus\GrootLib\Entity\User\UserSourceType;

/**
 * Class SessionIdField
 *
 * @package Noobus\GrootLib\Storage\Clickhouse\Entity\Field
 */
class UserSourceTypeField implements FieldInterface
{
    /**
     * @var string
     */
    private $sourceType;

    /**
     *
     */
    protected const NAME = 'UserSourceType';

    /**
     * EventTypeField constructor.
     *
     * @param string $sourceType
     */
    public function __construct(string $sourceType)
    {
        $this->sourceType = $sourceType;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return UserSourceType::isValidType($this->sourceType);
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->sourceType;
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
