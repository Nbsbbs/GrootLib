<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Service;

use Noobus\GrootLib\Buffer\EventBufferInterface;
use Noobus\GrootLib\Storage\EventStorageInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class EventProcessor implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var EventBufferInterface
     */
    private $buffer;

    /**
     * @var EventStorageInterface
     */
    private $storage;

    /**
     * EventProcessor constructor.
     *
     * @param EventBufferInterface $buffer
     * @param EventStorageInterface $storage
     */
    public function __construct(EventBufferInterface $buffer, EventStorageInterface $storage)
    {
        $this->buffer = $buffer;
        $this->storage = $storage;
        $this->logger = new NullLogger();
    }

    public function work() {
        while ($event = $this->buffer->get()) {
            $this->storage->save($event);
        }
    }
}
