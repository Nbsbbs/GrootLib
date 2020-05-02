<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage;

use Noobus\GrootLib\Entity\EventInterface;

interface EventStorageInterface
{
    public function save(EventInterface $event);
}
