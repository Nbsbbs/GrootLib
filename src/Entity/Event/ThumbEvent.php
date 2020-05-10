<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Event;

use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Entity\Item\ThumbItem;
use Noobus\GrootLib\Entity\ItemInterface;
use Noobus\GrootLib\Entity\UserInterface;
use Noobus\GrootLib\Entity\Zone\CategoryZone;
use Noobus\GrootLib\Entity\ZoneInterface;

class ThumbEvent implements EventInterface
{
    protected const VALID_ZONE_TYPES = [
        CategoryZone::class,
    ];

    /**
     * @var ThumbItem
     */
    private $thumb;

    /**
     * @var ZoneInterface
     */
    private $zone;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $zonePlaceId;

    /**
     * @var \DateTimeImmutable
     */
    private $timestamp;

    /**
     * ThumbEvent constructor.
     *
     * @param ThumbItem $thumb
     * @param ZoneInterface $zone
     * @param UserInterface $user
     * @param string $type
     * @param string $zonePlaceId
     * @param \DateTimeImmutable $timestamp
     */
    public function __construct(
        ThumbItem $thumb,
        ZoneInterface $zone,
        UserInterface $user,
        string $type,
        string $zonePlaceId,
        \DateTimeImmutable $timestamp
    ) {
        $this->validateZoneType($zone);

        $this->thumb = $thumb;
        $this->zone = $zone;
        $this->user = $user;
        $this->timestamp = $timestamp;
        if (EventType::isValidType($type)) {
            $this->type = $type;
        } else {
            throw new \InvalidArgumentException('Invalid type "' . $type . '"');
        }
        if ($this->isValidPlaceId($zonePlaceId)) {
            $this->zonePlaceId = $zonePlaceId;
        } else {
            throw new \InvalidArgumentException('Invalid zone place id "' . $zonePlaceId . '"');
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
        if (!is_numeric($place)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            'thumb' => $this->thumb,
            'zone' => $this->zone,
            'user' => $this->user,
            'type' => $this->type,
            'zonePlaceId' => $this->zonePlaceId,
            'timestamp' => $this->timestamp,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->thumb = $data['thumb'];
        $this->zone = $data['zone'];
        $this->user = $data['user'];
        $this->type = $data['type'];
        $this->zonePlaceId = $data['zonePlaceId'];
        $this->timestamp = $data['timestamp'];
    }

    public function getZone(): ZoneInterface
    {
        return $this->zone;
    }

    public function getItem(): ItemInterface
    {
        return $this->thumb;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function getZonePlaceId(): string
    {
        return $this->zonePlaceId;
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
