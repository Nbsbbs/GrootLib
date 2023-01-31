<?php

namespace Noobus\GrootLib\Statistics\Request;

class DomainGalleriesRequest
{
    /**
     * @var array
     */
    private array $ids;

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
    private int $minViews = 10;

    /**
     * SearchResultStatRequest constructor.
     *
     * @param string $statisticsGroup
     * @param string $domain
     * @param array $ids
     */
    public function __construct(
        string $statisticsGroup,
        string $domain,
        array  $ids
    ) {
        $this->statisticsGroup = $statisticsGroup;
        $this->domain = $domain;
        foreach ($ids as $id) {
            $this->pushItem($id);
        }
    }

    /**
     * @param int $item
     * @return $this
     */
    public function pushItem(int $item): self
    {
        $this->ids[] = $item;
        return $this;
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
     * @return int
     */
    public function getMinViews(): int
    {
        return $this->minViews;
    }

    /**
     * @return int[]
     */
    public function getIds(): array
    {
        return $this->ids;
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
