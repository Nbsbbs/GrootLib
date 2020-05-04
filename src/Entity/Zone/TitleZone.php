<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

use Noobus\GrootLib\Entity\ZoneInterface;
use Noobus\GrootLib\Entity\Zone\ItemValidation\ZoneOfNumericItemsTrait;

/**
 * Class CategoryZone
 *
 * @package Noobus\GrootLib\Entity\Zone
 */
class TitleZone extends AbstractZone implements ZoneInterface
{
    use ZoneOfNumericItemsTrait;
    /**
     * @var string
     */
    protected $type = ZoneType::TYPE_TITLE;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param string $language
     * @param string|null $group
     */
    public function __construct(string $domain, string $language = 'en', string $group = null)
    {
        $this->domain = $domain;
        $this->language = $language;
        $this->group = $group;
    }
}
