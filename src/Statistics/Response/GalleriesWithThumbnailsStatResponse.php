<?php

namespace Noobus\GrootLib\Statistics\Response;

use Noobus\GrootLib\Statistics\Clickhouse\GalleryWithThumbnailStat;

class GalleriesWithThumbnailsStatResponse
{
    /**
     * @var array
     */
    private array $galleries = [];

    /**
     * @var bool
     */
    private bool $isSorted = false;

    /**
     * @var float|null
     */
    private ?float $elapsedTime = null;

    /**
     * @var int
     */
    private int $totalRows = 0;

    /**
     * @return int
     */
    public function getTotalRows(): int
    {
        return $this->totalRows;
    }

    /**
     * @param int $totalRows
     */
    public function setTotalRows(int $totalRows): void
    {
        $this->totalRows = $totalRows;
    }

    public function pushItem(GalleryWithThumbnailStat $thumbnailStat)
    {
        $this->galleries[$thumbnailStat->getGalleryId()] = $thumbnailStat;
        $this->isSorted = false;
    }

    /**
     * @return float|null
     */
    public function getElapsedTime(): ?float
    {
        return $this->elapsedTime;
    }

    /**
     * @param float $time
     * @return $this
     */
    public function setElapsedTime(float $time): self
    {
        $this->elapsedTime = $time;
        return $this;
    }

    /**
     * @param int $thumbnailId
     * @return float|null
     */
    public function getCtr(int $galleryId, int $thumbnailId): ?float
    {
        foreach ($this->walkItems() as $item) {
            if (($item->getThumbnailId() === $thumbnailId) and ($item->getGalleryId() === $galleryId)) {
                return $item->getCtr();
            }
        }

        return null;
    }

    /**
     *
     */
    public function sortItems(): void
    {
        if (!$this->isSorted) {
            uasort($this->galleries, function (GalleryWithThumbnailStat $a, GalleryWithThumbnailStat $b) {
                if ($a->getCtr() === $b->getCtr()) {
                    return 0;
                }
                return ($a->getCtr() > $b->getCtr()) ? -1 : 1;
            });
            $this->isSorted = true;
        }
    }

    /**
     * @return int
     */
    public function getItemsCount(): int
    {
        return sizeof($this->galleries);
    }

    /**
     * @return \Generator
     */
    public function walkItems(): \Generator
    {
        yield from $this->galleries;
    }
}
