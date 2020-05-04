<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Item;

use Noobus\GrootLib\Entity\ItemInterface;

/**
 * Class CategoryItem
 *
 * @package Noobus\GrootLib\Entity\Item
 */
class CategoryItem implements ItemInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * CategoryItem constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
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
    public function getType(): string
    {
        return ItemType::TYPE_CATEGORY;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        if (empty($serialized)) {
            throw new \InvalidArgumentException('Invalid value '.$serialized.' for field "id"');
        }
        $this->id = $serialized;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [];
    }
}
