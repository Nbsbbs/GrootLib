<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Buffer;

use Noobus\GrootLib\Buffer\Gearman\GearmanClientFactory;
use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Buffer\EventBufferInterface;

class GearmanEventBuffer implements EventBufferInterface
{
    /**
     * @var GearmanClientFactory
     */
    private $clientFactory;

    /**
     * @var \GearmanClient
     */
    private $client;

    /**
     * GearmanEventBuffer constructor.
     *
     * @param GearmanClientFactory $clientFactory
     */
    public function __construct(GearmanClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function buffer(EventInterface $event)
    {
        $this->getClient()->doBackground($this->getBufferQueue(), $event->serialize());
    }

    /**
     * @return \GearmanClient
     */
    public function getClient(): \GearmanClient
    {
        if (!$this->client) {
            $this->client = $this->clientFactory->getClient();
        }
        return $this->client;
    }

    /**
     * @return string
     */
    public function getBufferQueue(): string
    {
        return $this->clientFactory->getQueuePrefix() . '_eventBuffer';
    }

    public function get(): ?EventInterface
    {
        // TODO: Implement get() method.
    }
}
