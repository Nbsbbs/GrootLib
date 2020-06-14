<?php

namespace Noobus\GrootLib\Statistics\Request;

/**
 * Class PlacesStatisticsRequest
 */
class PlacesStatRequest
{
    /**
     * @return int
     */
    public function getMaxClicks(): int
    {
        return $this->maxClicks;
    }
    /**
     *
     */
    private const DEFAULT_MIN_CLICKS = 1;

    /**
     *
     */
    private const DEFAULT_MAX_CLICKS = 8;

    /**
     *
     */
    private const DEFAULT_LIMIT = 120;

    /**
     *
     */
    private const DEFAULT_TIMESPAN = 30;

    /**
     * @var string
     */
    private $statisticsGroup;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var int
     */
    private $limit = self::DEFAULT_LIMIT;

    /**
     * @var int
     */
    private $timespan = self::DEFAULT_TIMESPAN;

    /**
     * @var int
     */
    private $minClicks = self::DEFAULT_MIN_CLICKS;

    /**
     * @var int
     */
    private $maxClicks = self::DEFAULT_MAX_CLICKS;

    /**
     * PlacesStatisticsRequest constructor.
     *
     * @param string $statisticsGroup
     * @param string $domain
     */
    public function __construct(string $statisticsGroup, string $domain)
    {
        $this->statisticsGroup = $statisticsGroup;
        $this->domain = $domain;
    }

    /**
     * @param int $minClicks
     * @return $this
     */
    public function withMinClicks(int $minClicks): self
    {
        $this->minClicks = $minClicks;
        return $this;
    }

    /**
     * @param int $maxClicks
     * @return $this
     */
    public function withMaxClicks(int $maxClicks): self
    {
        $this->maxClicks = $maxClicks;
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function withLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $days
     * @return $this
     */
    public function withTimespan(int $days): self
    {
        $this->timespan = $days;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatisticsGroup(): string
    {
        return $this->statisticsGroup;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getTimespan(): int
    {
        return $this->timespan;
    }

    /**
     * @return int
     */
    public function getMinClicks(): int
    {
        return $this->minClicks;
    }
}
