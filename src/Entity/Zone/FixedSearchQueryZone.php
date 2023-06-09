<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

use Nbsbbs\Common\Orientation\OrientationFactory;
use Nbsbbs\Common\Orientation\OrientationInterface;
use Noobus\GrootLib\Entity\ZoneInterface;
use Noobus\GrootLib\Entity\Zone\ItemValidation\ZoneOfNumericItemsTrait;

/**
 * Class CategoryZone
 *
 * @package Noobus\GrootLib\Entity\Zone
 */
class FixedSearchQueryZone extends AbstractZone implements ZoneInterface
{
    use ZoneOfNumericItemsTrait;

    /**
     * @var string
     */
    protected string $type = ZoneType::TYPE_FIXED_SEARCH_QUERY;

    /**
     * @var int
     */
    protected int $queryId;

    /**
     * @var string
     */
    protected string $fullTextQuery;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param int $queryId
     * @param string $fullTextQuery
     * @param string $language
     * @param string $group
     */
    public function __construct(
        string $domain,
        int $queryId,
        string $fullTextQuery,
        string $language = 'en',
        string $group = ''
    ) {
        $this->domain = $domain;
        $this->queryId = $queryId;
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
    public function __serialize()
    {
        return serialize([
            'd' => $this->domain,
            'g' => $this->group,
            'qid' => $this->queryId,
            'ftq' => $this->fullTextQuery,
            'l' => $this->language,
            'o' => $this->orientation->getCode(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function __unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->domain = $data['d'];
        $this->queryId = $data['qid'];
        $this->fullTextQuery = $data['ftq'];
        $this->group = $data['g'] ?? '';
        $this->language = $data['l'] ?? 'en';
        $this->orientation = OrientationFactory::make($data['o'] ?? OrientationInterface::STRAIGHT);
    }

    public function getSearchKeyword(): string
    {
        return $this->fullTextQuery;
    }

    /**
     * @return string
     */
    public function getSearchKeywordTranslation(): string
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
        return $this->queryId;
    }
}
