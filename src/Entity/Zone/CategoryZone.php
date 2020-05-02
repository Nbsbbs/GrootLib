<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

use Noobus\GrootLib\Entity\ZoneInterface;

/**
 * Class CategoryZone
 *
 * @package Noobus\GrootLib\Entity\Zone
 */
class CategoryZone implements ZoneInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string|null
     */
    private $parentId = null;

    /**
     * @var string|null
     */
    private $group = null;

    /**
     * @var string
     */
    private $type = ZoneType::TYPE_CATEGORY;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param string $id
     * @param string|null $parentId
     * @param string|null $group
     */
    public function __construct(string $domain, string $id, string $parentId = null, string $group = null)
    {
        $this->domain = $domain;
        $this->id = $id;
        $this->parentId = $parentId;
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
