<?php

namespace Noobus\GrootLib\Statistics\Request;

class SearchResultStatRequest
{
    /**
     * @var array
     */
    private array $thumbnailIds = [];

    /**
     * @var string
     */
    private string $statisticsGroup;

    /**
     * @var string
     */
    private string $domain;

    /**
     * @var string
     */
    private string $searchRequest;

    /**
     * @var int
     */
    private int $limit = 50;

    /**
     * @var int
     */
    private int $offset = 0;

    /**
     * @var int
     */
    private int $minViews = 100;

    /**
     * @var bool
     */
    private bool $ignoreDomain = false;

    /**
     * SearchResultStatRequest constructor.
     *
     * @param string $statisticsGroup
     * @param string $domain
     * @param string $searchRequest
     * @param array $thumbnailIds
     */
    public function __construct(
        string $statisticsGroup,
        string $domain,
        string $searchRequest,
        array $thumbnailIds = []
    ) {
        $this->statisticsGroup = $statisticsGroup;
        $this->searchRequest = $searchRequest;
        $this->domain = $domain;
        $this->thumbnailIds = $thumbnailIds;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function withLimitOffset(int $limit, int $offset): self
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param bool $ignoreDomain
     * @return $this
     */
    public function setIgnoreDomain(bool $ignoreDomain): self
    {
        $this->ignoreDomain = $ignoreDomain;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIgnoreDomain(): bool
    {
        return $this->ignoreDomain;
    }

    /**
     * @return int
     */
    public function getMinViews(): int
    {
        return $this->minViews;
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
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return array
     */
    public function getThumbnailIds(): array
    {
        return $this->thumbnailIds;
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
     * @return string
     */
    public function getSearchRequest(): string
    {
        return $this->searchRequest;
    }
}
