<?php

namespace Noobus\GrootLib\Statistics\Response;

use Noobus\GrootLib\Statistics\Clickhouse\Places\PlacementStat;

class PlacesStatResponse
{
    /**
     * @var array
     */
    private $places = [];

    /**
     * @var bool
     */
    private $isSorted = false;

    public function pushPlace(PlacementStat $placementStat) {
        $this->places[$placementStat->getId()] = $placementStat;
        $this->isSorted = false;
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
