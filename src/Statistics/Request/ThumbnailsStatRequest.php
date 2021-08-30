<?php

namespace Noobus\GrootLib\Statistics\Request;

use Nbsbbs\Common\ThumbnailIdentifier;

class ThumbnailsStatRequest
{
    private const DEFAULT_MIN_VIEWS = 100;

    /**
     * @var array
     */
    protected array $thumbnails;

    /**
     * @var string
     */
    protected string $statGroup;

    /**
     * @var string|null
     */
    protected ?string $domain = null;

    /**
     * @var int
     */
    protected int $minViews = self::DEFAULT_MIN_VIEWS;

    /**
     * @var string|null
     */
    protected ?string $customTableName = null;

    /**
     * @param array $thumbnails
     * @param string $statGroup
     * @param string|null $domain
     */
    public function __construct(array $thumbnails, string $statGroup, ?string $domain = null)
    {
        foreach ($thumbnails as $thumbnail) {
            if (!($thumbnail instanceof ThumbnailIdentifier)) {
                throw new \InvalidArgumentException('\$thumbnails must be an array or ThumbnailIdentifier');
            }
        }
        $this->thumbnails = $thumbnails;
        $this->statGroup = $statGroup;
        $this->domain = $domain;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function withTableName(string $table): self
    {
        $this->customTableName = $table;
        return $this;
    }

    /**
     * @param int $minViews
     * @return $this
     */
    public function withMinViews(int $minViews): self
    {
        if ($minViews < 0) {
            throw new \InvalidArgumentException('minViews must be positive integer or zero');
        }
        $this->minViews = $minViews;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomTableName(): string
    {
        return $this->customTableName;
    }

    /**
     * @return int
     */
    public function getMinViews(): int
    {
        return $this->minViews;
    }

    /**
     * @return array
     */
    public function getThumbnails(): array
    {
        return $this->thumbnails;
    }

    /**
     * @return string
     */
    public function getStatGroup(): string
    {
        return $this->statGroup;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }
}
