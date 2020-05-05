<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

class DateTimeField implements FieldInterface
{
    /**
     * @var \DateTimeImmutable
     */
    private $date;

    protected const TIME_ZONE = 'Europe/Moscow';
    protected const NAME = 'EventDateTime';

    public function __construct(\DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function isValid()
    {
        return true;
    }

    public function value()
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public static function name(): string
    {
        return self::NAME;
    }

    public static function toSql(): string
    {
        return sprintf('`%s` DateTime(\'%s\')', static::name(), self::TIME_ZONE);
    }
}
