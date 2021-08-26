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
    private string $id;

    /**
     * @var string
     */
    private string $galleryId;

    /**
     * ThumbItem constructor.
     *
     * @param string $id
     * @param string $galleryId
     */
    public function __construct(string $id, string $galleryId)
    {
        if (intval($id) < 1) {
            throw new \InvalidArgumentException('Thumb id must be positive integer: '.$id);
        }
        if (intval($galleryId) < 1) {
            throw new \InvalidArgumentException('Gallery id must be positive integer: '.$id);
        }
        $this->id = $id;
        $this->galleryId = $galleryId;
    }

    /**
     * @return int
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return string
     */
    public function getGalleryId(): string
    {
        return (string) $this->galleryId;
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
        return serialize([
            'id' => $this->id,
            'galleryId' => $this->galleryId,
            ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        if (empty($serialized)) {
            throw new \InvalidArgumentException('Invalid value '.$serialized.' for field "id"');
        }
        $unserialized = unserialize($serialized);
        $this->id = $unserialized['id'];
        $this->galleryId = $unserialized['galleryId'];
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [];
    }
}
