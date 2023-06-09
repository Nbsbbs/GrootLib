<?php

namespace Nbsbbs\Common\Orientation;

class Gay implements OrientationInterface
{
    /**
     * @return bool
     */
    public function isGay(): bool
    {
        return true;
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
        return false;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return OrientationInterface::GAY;
    }

    /**
     * @param OrientationInterface $orientation
     * @return bool
     */
    public function sameAs(OrientationInterface $orientation): bool
    {
        return $orientation->getCode() === OrientationInterface::GAY;
    }
}
