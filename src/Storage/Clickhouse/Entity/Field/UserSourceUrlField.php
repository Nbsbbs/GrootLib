<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

use Noobus\GrootLib\Entity\User\UserSourceType;

/**
 * Class SessionIdField
 *
 * @package Noobus\GrootLib\Storage\Clickhouse\Entity\Field
 */
class UserSourceUrlField implements FieldInterface
{
    /**
     * @var string
     */
    private $sourceUrl;

    /**
     *
     */
    protected const NAME = 'UserSourceUrl';

    /**
     * EventTypeField constructor.
     *
     * @param string $sourceUrl
     */
    public function __construct(string $sourceUrl = '')
    {
        $this->sourceUrl = $sourceUrl;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($url = filter_var($this->sourceUrl, FILTER_VALIDATE_URL)) {
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
    public function value()
    {
        return $this->sourceUrl;
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
