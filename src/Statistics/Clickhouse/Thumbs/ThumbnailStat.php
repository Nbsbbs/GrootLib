<?php

namespace Noobus\GrootLib\Statistics\Clickhouse\Thumbs;

class ThumbnailStat
{
    /**
     * @var int
     */
    private int $id;

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
     * ThumbnailStat constructor.
     *
     * @param int $id
     * @param int $clicks
     * @param int $views
     * @param float $ctr
     */
    public function __construct(int $id, int $clicks, int $views, float $ctr)
    {
        $this->id = $id;
        $this->clicks = $clicks;
        $this->views = $views;
        $this->ctr = $ctr;
    }

    /**
     * @return int
     */
    public function getId(): int
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
