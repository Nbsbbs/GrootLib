<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

use Nbsbbs\Common\Orientation\OrientationInterface;

class ZoneOrientationField implements FieldInterface
{
    protected const NAME = 'ZoneOrientation';

    /**
     * @var OrientationInterface
     */
    private OrientationInterface $orientation;

    public function __construct(OrientationInterface $orientation)
    {
        $this->orientation = $orientation;
    }

    public function isValid()
    {
        return true;
    }

    public function value()
    {
        return $this->orientation->getCode();
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
