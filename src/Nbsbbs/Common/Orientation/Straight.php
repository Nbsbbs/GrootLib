<?php

namespace Nbsbbs\Common\Orientation;

class Straight implements OrientationInterface
{
    /**
     * @return bool
     */
    public function isGay(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isShemale(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isStraight(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return OrientationInterface::STRAIGHT;
    }

    /**
     * @param OrientationInterface $orientation
     * @return bool
     */
    public function sameAs(OrientationInterface $orientation): bool
    {
        return $orientation->getCode() === OrientationInterface::STRAIGHT;
    }
}
