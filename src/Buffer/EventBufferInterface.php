<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Buffer;

use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Storage\EventStorageInterface;

interface EventBufferInterface
{
    public function buffer(EventInterface $event);

    /**
     * @param EventStorageInterface $eventStorage
     */
    public function subscribe(EventStorageInterface $eventStorage): void;
}
