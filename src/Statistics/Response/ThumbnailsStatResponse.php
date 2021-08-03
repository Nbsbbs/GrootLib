<?php

namespace Noobus\GrootLib\Statistics\Response;

use Noobus\GrootLib\Statistics\Clickhouse\Thumbs\ThumbnailStat;

class ThumbnailsStatResponse
{
    /**
     * @var array
     */
    private array $thumbnails = [];

    /**
     * @var bool
     */
    private bool $isSorted = false;

    /**
     * @var float|null
     */
    private ?float $elapsedTime = null;

    public function pushItem(ThumbnailStat $thumbnailStat)
    {
        $this->thumbnails[$thumbnailStat->getId()] = $thumbnailStat;
        $this->isSorted = false;
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
     * @return float|null
     */
    public function getElapsedTime(): ?float
    {
        return $this->elapsedTime;
    }

    /**
     * @param int $thumbnailId
     * @return float|null
     */
    public function getThumbnailCtr(int $thumbnailId): ?float
    {
        foreach ($this->walkItems() as $item) {
            if ($item->getId() === $thumbnailId) {
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
            uasort($this->thumbnails, function (ThumbnailStat $a, ThumbnailStat $b) {
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
    public function getTotalItems(): int
    {
        return sizeof($this->thumbnails);
    }

    /**
     * @return \Generator
     */
    public function walkItems(): \Generator
    {
        $this->sortItems();
        yield from $this->thumbnails;
    }
}
