<?php

namespace Nbsbbs\Common\Orientation;

class OrientationFactory
{
    /**
     * @param string $orient
     * @return OrientationInterface
     */
    public static function make(string $orient): OrientationInterface
    {
        if ($orient === OrientationInterface::GAY) {
            return new Gay();
        } elseif ($orient === OrientationInterface::SHEMALE) {
            return new Shemale();
        } elseif ($orient === OrientationInterface::STRAIGHT) {
            return new Straight();
        } else {
            throw new \InvalidArgumentException('Invalid orientation code "'.$orient.'"');
        }
    }
}
