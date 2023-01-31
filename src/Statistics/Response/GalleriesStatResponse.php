<?php

namespace Noobus\GrootLib\Statistics\Response;

use Noobus\GrootLib\Statistics\Clickhouse\Galleries\GalleryStat;

class GalleriesStatResponse
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

    public function pushItem(GalleryStat $galleryStat)
    {
        $this->galleries[$galleryStat->getId()] = $galleryStat;
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
     * @param int $galleryId
     * @return float|null
     */
    public function getCtr(int $galleryId): ?float
    {
        foreach ($this->walkItems() as $item) {
            if ($item->getId() === $galleryId) {
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
            uasort($this->galleries, function (GalleryStat $a, GalleryStat $b) {
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
     * @return \Generator|GalleryStat[]
     */
    public function walkItems(): \Generator
    {
        $this->sortItems();
        yield from $this->galleries;
    }

    /**
     * @return array
     */
    public function ids(): array
    {
        $ids = [];
        foreach ($this->galleries as $item) {
            $ids[] = $item->getId();
        }

        return $ids;
    }
}
