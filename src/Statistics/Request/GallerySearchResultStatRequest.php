<?php

namespace Noobus\GrootLib\Statistics\Request;

class GallerySearchResultStatRequest
{
    public const DEFAULT_THUMB_RATING = 0.0102739;

    /**
     * @var array
     */
    private array $galleryIds;

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
    private string $translatedSearchQuery;

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
    private int $minViews = 10;

    /**
     * @var float
     */
    private float $defaultCtr = self::DEFAULT_THUMB_RATING;

    /**
     * SearchResultStatRequest constructor.
     *
     * @param string $statisticsGroup
     * @param string $domain
     * @param string $searchRequest
     * @param array $galleryIds
     */
    public function __construct(
        string $statisticsGroup,
        string $domain,
        string $searchRequest,
        array $galleryIds
    ) {
        $this->statisticsGroup = $statisticsGroup;
        $this->translatedSearchQuery = $searchRequest;
        $this->domain = $domain;
        $this->galleryIds = $galleryIds;
    }

    /**
     * @param int $minViews
     * @return $this
     */
    public function withMinViews(int $minViews): self
    {
        $this->minViews = $minViews;
        return $this;
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
     * @param float $ctr
     * @return $this
     */
    public function withDefaultCtr(float $ctr): self
    {
        $this->defaultCtr = $ctr;
        return $this;
    }

    /**
     * @return float
     */
    public function getDefaultCtr(): float
    {
        return $this->defaultCtr;
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
    public function getGalleryIds(): array
    {
        return $this->galleryIds;
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
    public function getTranslatedSearchQuery(): string
    {
        return $this->translatedSearchQuery;
    }
}
