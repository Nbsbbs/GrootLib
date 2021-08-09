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
class EmbedZone extends AbstractZone implements ZoneInterface
{
    use ZoneOfNumericItemsTrait;
    /**
     * @var string
     */
    protected string $type = ZoneType::TYPE_EMBED;

    /**
     * @var int
     */
    protected int $embedId;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param string $language
     * @param string $group
     */
    public function __construct(int $embedId, string $domain, string $language = 'en', string $group = '')
    {
        $this->domain = $domain;
        $this->language = $language;
        $this->group = $group;
        $this->embedId = $embedId;
    }

    public function getCategoryId(): ?int
    {
        return 0;
    }

    public function getSearchKeyword(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getSearchKeywordTranslation(): string
    {
        return '';
    }

    /**
     * @return int
     */
    public function getEmbedId(): int
    {
        return $this->embedId;
    }
}
