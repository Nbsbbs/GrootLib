<?php

namespace Noobus\GrootLib\Statistics\Clickhouse;

class GalleryWithThumbnailStat
{
    /**
     * @var int
     */
    protected int $galleryId;

    /**
     * @var int
     */
    private int $thumbnailId;

    /**
     * @var int
     */
    private int $clicks;

    /**
     * @var int
     */
    private int $views;

    /**
     * @var float
     */
    private float $ctr;

    /**
     * @param int $galleryId
     * @param int $thumbnailId
     * @param int $clicks
     * @param int $views
     * @param float $ctr
     */
    public function __construct(int $galleryId, int $thumbnailId, int $clicks, int $views, float $ctr)
    {
        if ($galleryId <= 0) {
            throw new \InvalidArgumentException('Gallery id must be positive integer');
        }
        if ($thumbnailId <= 0) {
            throw new \InvalidArgumentException('Thumbnail id must be positive integer');
        }
        $this->thumbnailId = $thumbnailId;
        $this->clicks = $clicks;
        $this->views = $views;
        $this->ctr = $ctr;
        $this->galleryId = $galleryId;
    }

    /**
     * @return int
     */
    public function getThumbnailId(): int
    {
        return $this->thumbnailId;
    }

    /**
     * @param int $thumbnailId
     */
    public function setThumbnailId(int $thumbnailId): void
    {
        $this->thumbnailId = $thumbnailId;
    }

    /**
     * @return int
     */
    public function getClicks(): int
    {
        return $this->clicks;
    }

    /**
     * @param int $clicks
     */
    public function setClicks(int $clicks): void
    {
        $this->clicks = $clicks;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return float
     */
    public function getCtr(): float
    {
        return $this->ctr;
    }

    /**
     * @param float $ctr
     */
    public function setCtr(float $ctr): void
    {
        $this->ctr = $ctr;
    }

    /**
     * @return int
     */
    public function getGalleryId(): int
    {
        return $this->galleryId;
    }

    /**
     * @param int $galleryId
     */
    public function setGalleryId(int $galleryId): void
    {
        $this->galleryId = $galleryId;
    }
}
