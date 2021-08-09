<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class ZoneSearchKeywordTranslationField implements FieldInterface
{
    protected const NAME = 'ZoneSearchKeywordTranslation';

    /**
     * @var string
     */
    private string $keyword;

    /**
     * @param string $keyword
     */
    public function __construct(string $keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->keyword;
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
