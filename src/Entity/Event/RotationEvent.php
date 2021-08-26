<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Event;

use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Entity\Item\ThumbItem;
use Noobus\GrootLib\Entity\ItemInterface;
use Noobus\GrootLib\Entity\UserInterface;
use Noobus\GrootLib\Entity\Zone\CategoryZone;
use Noobus\GrootLib\Entity\Zone\EmbedZone;
use Noobus\GrootLib\Entity\Zone\FixedSearchQueryZone;
use Noobus\GrootLib\Entity\Zone\FixedTopQueryZone;
use Noobus\GrootLib\Entity\Zone\SearchQueryZone;
use Noobus\GrootLib\Entity\Zone\TitleZone;
use Noobus\GrootLib\Entity\ZoneInterface;

class RotationEvent implements EventInterface
{
    protected const VALID_ZONE_TYPES = [
        CategoryZone::class,
        TitleZone::class,
        FixedSearchQueryZone::class,
        FixedTopQueryZone::class,
        SearchQueryZone::class,
        EmbedZone::class,
    ];

    /**
     * @var ThumbItem
     */
    private ThumbItem $thumb;

    /**
     * @var string
     */
    private string $rotationId;

    /**
     * @var ZoneInterface
     */
    private ZoneInterface $zone;

    /**
     * @var UserInterface
     */
    private UserInterface $user;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $pagePlaceId;

    /**
     * @var \DateTimeImmutable
     */
    private \DateTimeImmutable $timestamp;

    /**
     * @var string
     */
    private string $eventUrl = '';

    /**
     * ThumbEvent constructor.
     *
     * @param string $eventType
     * @param string $rotationId
     * @param string $pagePlaceId
     * @param ThumbItem $thumb
     * @param UserInterface $user
     * @param \DateTimeImmutable $timestamp
     */
    public function __construct(
        string             $eventType,
        string             $rotationId,
        string             $pagePlaceId,
        ThumbItem          $thumb,
        UserInterface      $user,
        ZoneInterface      $zone,
        \DateTimeImmutable $timestamp
    ) {
        $this->thumb = $thumb;
        $this->user = $user;
        $this->rotationId = $rotationId;
        $this->timestamp = $timestamp;
        $this->validateZoneType($zone);
        $this->zone = $zone;

        if (EventType::isValidType($eventType)) {
            $this->type = $eventType;
        } else {
            throw new \InvalidArgumentException('Invalid event type "' . $eventType . '"');
        }
        if ($this->isValidPlaceId($pagePlaceId)) {
            $this->pagePlaceId = $pagePlaceId;
        } else {
            throw new \InvalidArgumentException('Invalid page place id "' . $pagePlaceId . '"');
        }
    }

    /**
     * @param ZoneInterface $zone
     */
    protected function validateZoneType(ZoneInterface $zone): void
    {
        $class = get_class($zone);
        if (!in_array($class, self::VALID_ZONE_TYPES)) {
            throw new \InvalidArgumentException('Zone type not supported');
        }
    }

    /**
     * @param string $place
     * @return bool
     */
    protected function isValidPlaceId(string $place): bool
    {
        return is_numeric($place);
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            'thumb' => $this->thumb,
            'user' => $this->user,
            'type' => $this->type,
            'zone' => $this->zone,
            'rotationId' => $this->rotationId,
            'pagePlaceId' => $this->pagePlaceId,
            'timestamp' => $this->timestamp,
            'eventUrl' => $this->eventUrl,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->thumb = $data['thumb'];
        $this->user = $data['user'];
        $this->type = $data['type'];
        $this->zone = $data['zone'];
        $this->rotationId = $data['rotationId'];
        $this->pagePlaceId = $data['pagePlaceId'];
        $this->timestamp = $data['timestamp'];
        $this->eventUrl = $data['eventUrl'] ?? '';
    }

    /**
     * @param string $url
     * @return $this
     */
    public function withEventUrl(string $url): self
    {
        $this->eventUrl = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventUrl(): string
    {
        return $this->eventUrl;
    }

    public function getZone(): ZoneInterface
    {
        return $this->zone;
    }

    public function getItem(): ItemInterface
    {
        return $this->thumb;
    }

    public function getEventType(): string
    {
        return $this->type;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function getPagePlaceId(): string
    {
        return $this->pagePlaceId;
    }

    public function getRotationId(): string
    {
        return $this->rotationId;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getAttributes(): array
    {
        return [];
    }

    public function getMetrics(): array
    {
        return [];
    }

    public function isValid(): bool
    {
        if (!$this->zone->isValidItemId($this->thumb->getId())) {
            return false;
        }

        return true;
    }
}
