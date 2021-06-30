<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Service;

use Noobus\GrootLib\Buffer\EventBufferInterface;
use Noobus\GrootLib\Entity\EventInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class EventReceiver implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var EventBufferInterface
     */
    private $eventBuffer;

    /**
     * EventReceiver constructor.
     *
     * @param EventBufferInterface $eventBuffer
     */
    public function __construct(EventBufferInterface $eventBuffer)
    {
        $this->eventBuffer = $eventBuffer;
        $this->logger = new NullLogger();
    }

    /**
     * @param EventInterface $event
     *
     */
    public function register(EventInterface $event): void
    {
        if ($event->isValid()) {
            $this->eventBuffer->buffer($event);
        } else {
            $this->logger->notice('Invalid event');
        }
    }
}
