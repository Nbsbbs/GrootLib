<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity;

interface EventInterface extends \Serializable
{
    public function getZone(): ZoneInterface;
    public function getItem(): ItemInterface;
    public function getType(): string;
    public function getTimestamp(): \DateTimeImmutable;

    public function isValid(): bool;
}
