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
class CategoryZone extends AbstractZone implements ZoneInterface
{
    use ZoneOfNumericItemsTrait;

    /**
     * @var string
     */
    protected $type = ZoneType::TYPE_CATEGORY;

    /**
     * @var int
     */
    protected $categoryId;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param int $categoryId
     * @param string $language
     * @param string|null $group
     */
    public function __construct(
        string $domain,
        int $categoryId = 0,
        string $language = 'en',
        string $group = null
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
    }

    public function getSearchKeyword(): string
    {
        return '';
    }

    /**
     * @return int
     */
    public function getEmbedId(): int
    {
        return 0;
    }
}
