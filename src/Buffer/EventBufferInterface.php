<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Buffer;

use Noobus\GrootLib\Entity\EventInterface;

interface EventBufferInterface
{
    public function buffer(EventInterface $event);

    /**
     * @return \Generator|EventInterface[]
     */
    public function subscribe(): \Generator;
}
