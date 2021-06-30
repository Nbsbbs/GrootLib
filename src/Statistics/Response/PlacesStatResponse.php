<?php

namespace Noobus\GrootLib\Statistics\Response;

use Noobus\GrootLib\Statistics\Clickhouse\Places\PlacementStat;

class PlacesStatResponse
{
    /**
     * @var array
     */
    private array $places = [];

    /**
     * @var bool
     */
    private bool $isSorted = false;

    /**
     * @var float|null
     */
    private ?float $elapsedTime = null;

    public function pushPlace(PlacementStat $placementStat)
    {
        $this->places[$placementStat->getId()] = $placementStat;
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
     *
     */
    public function sortPlaces(): void
    {
        if (!$this->isSorted) {
            ksort($this->places, SORT_NATURAL);
            $this->isSorted = true;
        }
    }

    /**
     * @return int
     */
    public function getTotalPlaces(): int
    {
        return sizeof($this->places);
    }

    /**
     * @return \Generator
     */
    public function walkItems(): \Generator
    {
        $this->sortPlaces();
        yield from $this->places;
    }
}
