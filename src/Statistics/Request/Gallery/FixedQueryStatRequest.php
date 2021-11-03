<?php

namespace Noobus\GrootLib\Statistics\Request\Gallery;

use Noobus\GrootLib\Statistics\Request\Item\IdWithDefaultCtr;

class FixedQueryStatRequest
{
    /**
     * @var array
     */
    private array $items;

    /**
     * @var string
     */
    private string $statisticsGroup;

    /**
     * @var string
     */
    private string $domain;

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
     * @var int
     */
    private int $queryId;

    /**
     * SearchResultStatRequest constructor.
     *
     * @param string $statisticsGroup
     * @param string $domain
     * @param int $queryId
     */
    public function __construct(
        string $statisticsGroup,
        string $domain,
        int $queryId
    ) {
        $this->statisticsGroup = $statisticsGroup;
        $this->domain = $domain;
        $this->queryId = $queryId;
    }

    /**
     * @param IdWithDefaultCtr $item
     * @return $this
     */
    public function pushItem(IdWithDefaultCtr $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @return int
     */
    public function getQueryId(): int
    {
        return $this->queryId;
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
     * @return IdWithDefaultCtr[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function getIds(): array
    {
        $result = [];
        foreach ($this->items as $item) {
            $result[] = $item->getId();
        }
        return $result;
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
}
