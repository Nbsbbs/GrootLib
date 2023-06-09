<?php

namespace Nbsbbs\Common\Orientation;

class Shemale implements OrientationInterface
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
        return true;
    }

    /**
     * @return bool
     */
    public function isStraight(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return OrientationInterface::SHEMALE;
    }

    /**
     * @param OrientationInterface $orientation
     * @return bool
     */
    public function sameAs(OrientationInterface $orientation): bool
    {
        return $orientation->getCode() === OrientationInterface::SHEMALE;
    }
}
