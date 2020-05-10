<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Service;

use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Buffer\EventBufferInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EventReceiver implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var EventBufferInterface
     */
    private $eventBuffer;

    /**
     * @var LoggerInterface
     */
    private $logger;

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
