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
        int $categoryId,
        string $language = 'en',
        string $group = null
    ) {
        $this->domain = $domain;
        $this->categoryId = $categoryId;
        $this->group = $group;
        $this->language = $language;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            'domain' => $this->domain,
            'group' => $this->group,
            'categoryId' => $this->categoryId,
            'lang' => $this->language,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->domain = $data['domain'];
        $this->categoryId = $data['categoryId'];
        $this->group = $data['group'] ?? null;
        $this->language = $data['lang'] ?? 'en';
    }
}
