<?php

namespace Noobus\GrootLib\Statistics\Request;

class DomainSearchStatRequest extends AbstractRequest
{
    /**
     * @var string
     */
    private string $zoneGroup;

    /**
     * @var string|null
     */
    private ?string $domain = null;

    /**
     * @var string
     */
    private string $translatedQuery;

    /**
     * @var int
     */
    private int $minViews = 25;

    /**
     * @param string $zoneGroup
     * @param string $translatedQuery
     * @param string|null $domain
     */
    public function __construct(string $zoneGroup, string $translatedQuery, ?string $domain = null)
    {
        $this->zoneGroup = $zoneGroup;
        $this->translatedQuery = $translatedQuery;
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getZoneGroup(): string
    {
        return $this->zoneGroup;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @return string
     */
    public function getTranslatedQuery(): string
    {
        return $this->translatedQuery;
    }

    /**
     * @return int
     */
    public function getMinViews(): int
    {
        return $this->minViews;
    }

    /**
     * @param int $minViews
     * @return DomainSearchStatRequest
     */
    public function withtMinViews(int $minViews): self
    {
        $this->minViews = $minViews;
        return $this;
    }
}
