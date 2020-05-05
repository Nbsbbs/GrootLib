<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class ZoneDomainField implements FieldInterface
{
    protected const NAME = 'ZoneDomain';

    /**
     * @var string
     */
    private $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    public function isValid()
    {
        return true;
    }

    public function value()
    {
        return $this->domain;
    }

    public static function name(): string
    {
        return self::NAME;
    }

    public static function toSql(): string
    {
        return sprintf('`%s` String', static::name());
    }
}
