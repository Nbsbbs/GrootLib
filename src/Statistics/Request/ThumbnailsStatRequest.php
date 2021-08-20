<?php

namespace Noobus\GrootLib\Statistics\Request;

use Nbsbbs\Common\ThumbnailIdentifier;

class ThumbnailsStatRequest
{
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
