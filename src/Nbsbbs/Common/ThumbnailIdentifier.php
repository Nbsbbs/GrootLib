<?php

namespace Nbsbbs\Common;

/**
 * Class ThumbnailIdentifier
 *
 * @package Nbsbbs\Common
 */
class ThumbnailIdentifier
{
    /**
     * @var int
     */
    private int $galleryId;

    /**
     * @var int
     */
    private int $thumbnailId;

    /**
     * ThumbnailIdentifier constructor.
     *
     * @param int $galleryId
     * @param int $thumbnailId
     */
    public function __construct(int $galleryId, int $thumbnailId)
    {
        $this->galleryId = $galleryId;
        $this->thumbnailId = $thumbnailId;
    }

    /**
     * @return int
     */
    public function getGalleryId(): int
    {
        return $this->galleryId;
    }

    /**
     * @return int
     */
    public function getThumbnailId(): int
    {
        return $this->thumbnailId;
    }
}
