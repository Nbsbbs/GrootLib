<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone\ItemValidation;

/**
 * Trait ZoneOfNumericItemsTrait
 *
 * Use for zones, which contain only numeric items
 *
 * @package Noobus\GrootLib\Entity\Zone
 */
trait ZoneOfNumericItemsTrait
{
    /**
     * @param string $id
     * @return bool
     */
    public function isValidItemId(string $id): bool
    {
        if (!preg_match('#^\d+$#s', $id)) {
            return false;
        } else {
            return true;
        }
    }
}
