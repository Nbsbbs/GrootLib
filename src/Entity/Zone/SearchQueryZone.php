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
    protected string $query;

    /**
     * @var string
     */
    private string $translatedQuery;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param string $query
     * @param string $translatedQuery
     * @param string $language
     * @param string $group
     */
    public function __construct(
        string $domain,
        string $query,
        string $translatedQuery,
        string $language = 'en',
        string $group = ''
    ) {
        $this->domain = $domain;
        $this->query = $query;
        $this->translatedQuery = $translatedQuery;
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
            'q' => $this->query,
            'trq' => $this->translatedQuery,
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
        $this->query = $data['q'];
        $this->translatedQuery = $data['trq'];
        $this->group = $data['g'] ?? '';
        $this->language = $data['l'] ?? 'en';
    }

    /**
     * @return string
     */
    public function getSearchKeyword(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getSearchKeywordTranslation(): string
    {
        return $this->translatedQuery;
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
