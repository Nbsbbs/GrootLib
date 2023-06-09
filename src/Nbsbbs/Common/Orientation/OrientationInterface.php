<?php

namespace Nbsbbs\Common\Orientation;

interface OrientationInterface
{
    public const GAY = 'gay';
    public const STRAIGHT = 'straight';
    public const SHEMALE = 'shemale';

    /**
     * @return bool
     */
    public function isGay(): bool;

    /**
     * @return bool
     */
    public function isShemale(): bool;

    /**
     * @return bool
     */
    public function isStraight(): bool;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param OrientationInterface $orientation
     * @return bool
     */
    public function sameAs(OrientationInterface $orientation): bool;
}
