<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Item;

use Noobus\GrootLib\Entity\ItemInterface;

/**
 * Class ThumbItem
 *
 * @package Noobus\GrootLib\Entity\Item
 */
class ThumbItem implements ItemInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * ThumbItem constructor.
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
        return ItemType::TYPE_THUMB;
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
