<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class ZoneLanguageField implements FieldInterface
{
    protected const NAME = 'ZoneLanguage';

    /**
     * @var string
     */
    private $language;

    public function __construct(string $language)
    {
        $this->language = $language;
    }

    public function isValid()
    {
        return true;
    }

    public function value()
    {
        return $this->language;
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
