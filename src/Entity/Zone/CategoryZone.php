<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

use Nbsbbs\Common\Orientation\OrientationFactory;
use Nbsbbs\Common\Orientation\OrientationInterface;
use Noobus\GrootLib\Entity\Zone\ItemValidation\ZoneOfNumericItemsTrait;
use Noobus\GrootLib\Entity\ZoneInterface;

/**
 * Class CategoryZone
 *
 * @package Noobus\GrootLib\Entity\Zone
 */
class CategoryZone extends AbstractZone implements ZoneInterface
{
    use ZoneOfNumericItemsTrait;

    /**
     * @var string
     */
    protected string $type = ZoneType::TYPE_CATEGORY;

    /**
     * @var int
     */
    protected int $categoryId;

    /**
     * @var string
     */
    protected string $searchQuery = '';

    /**
     * @var string
     */
    protected string $translatedSearchQuery = '';

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param int $categoryId
     * @param string $language
     * @param string $group
     */
    public function __construct(
        string $domain,
        int    $categoryId = 0,
        string $language = 'en',
        string $group = ''
    ) {
        $this->domain = $domain;
        $this->categoryId = $categoryId;
        $this->group = $group;
        $this->language = $language;
    }

    /**
     * @return int
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            'd' => $this->domain,
            'g' => $this->group,
            'c' => $this->categoryId,
            'l' => $this->language,
            'q' => $this->searchQuery,
            'trq' => $this->translatedSearchQuery,
            'o' => $this->orientation->getCode(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->domain = $data['d'];
        $this->categoryId = $data['c'];
        $this->group = $data['g'] ?? null;
        $this->language = $data['l'] ?? 'en';
        $this->searchQuery = $data['q'] ?? '';
        $this->orientation = OrientationFactory::make($data['o'] ?? OrientationInterface::STRAIGHT);
        $this->translatedSearchQuery = $data['trq'] ?? '';
    }

    /**
     * @param string $searchQuery
     * @param string $translatedSearchQuery
     * @return CategoryZone
     */
    public function withSearchQuery(string $searchQuery, string $translatedSearchQuery = ''): self
    {
        if ($translatedSearchQuery === '') {
            $translatedSearchQuery = $searchQuery;
        }
        $this->searchQuery = $searchQuery;
        $this->translatedSearchQuery = $translatedSearchQuery;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchKeyword(): string
    {
        return $this->searchQuery;
    }

    /**
     * @return string
     */
    public function getSearchKeywordTranslation(): string
    {
        return $this->translatedSearchQuery;
    }

    /**
     * @return int
     */
    public function getEmbedId(): int
    {
        return 0;
    }
}
