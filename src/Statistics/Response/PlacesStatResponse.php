<?php

namespace Noobus\GrootLib\Statistics\Response;

use Noobus\GrootLib\Statistics\Clickhouse\Places\PlacementStat;

class PlacesStatResponse
{
    /**
     * @var array
     */
    private $places = [];

    public function pushPlace(PlacementStat $placementStat) {
        $this->places[$placementStat->getId()] = $placementStat;
        ksort($this->places, SORT_NATURAL);
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
        yield from $this->places;
    }
}
