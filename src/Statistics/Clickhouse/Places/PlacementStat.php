<?php

namespace Noobus\GrootLib\Statistics\Clickhouse\Places;

class PlacementStat
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $clicks;

    /**
     * @var int
     */
    private $views;

    /**
     * @var float
     */
    private $ctr;

    public function __construct(string $id, int $clicks, int $views, float $ctr)
    {
        $this->id = $id;
        $this->clicks = $clicks;
        $this->views = $views;
        $this->ctr = $ctr;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getClicks(): int
    {
        return $this->clicks;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @return float
     */
    public function getCtr(): float
    {
        return $this->ctr;
    }
}
