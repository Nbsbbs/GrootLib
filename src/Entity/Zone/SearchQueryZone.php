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
class SearchQueryZone extends AbstractZone implements ZoneInterface
{
    use ZoneOfNumericItemsTrait;

    /**
     * @var string
     */
    protected string $type = ZoneType::TYPE_SEARCH;

    /**
     * @var string
     */
    protected string $fullTextQuery;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param string $fullTextQuery
     * @param string $language
     * @param string $group
     */
    public function __construct(
        string $domain,
        string $fullTextQuery,
        string $language = 'en',
        string $group = ''
    ) {
        $this->domain = $domain;
        $this->fullTextQuery = $fullTextQuery;
        $this->group = $group;
        $this->language = $language;
    }

    /**
     * @return int
     */
    public function getCategoryId(): ?int
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            'd' => $this->domain,
            'g' => $this->group,
            'ftq' => $this->fullTextQuery,
            'l' => $this->language,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->domain = $data['d'];
        $this->fullTextQuery = $data['ftq'];
        $this->group = $data['g'] ?? '';
        $this->language = $data['l'] ?? 'en';
    }

    public function getSearchKeyword(): string
    {
        return $this->fullTextQuery;
    }

    /**
     * @return int
     */
    public function getEmbedId(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getFixedSearchId(): int
    {
        return 0;
    }
}
