<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Buffer;

use Noobus\GrootLib\Buffer\Gearman\GearmanClientFactory;
use Noobus\GrootLib\Buffer\Gearman\GearmanWorkerFactory;
use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Buffer\EventBufferInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class GearmanEventBuffer implements EventBufferInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var GearmanClientFactory
     */
    private $clientFactory;

    /**
     * @var GearmanWorkerFactory
     */
    private $workerFactory;

    /**
     * @var \GearmanClient
     */
    private $client;

    /**
     * @var \GearmanWorker
     */
    private $worker;

    /**
     * @var array
     */
    private $localBuffer = [];

    /**
     * GearmanEventBuffer constructor.
     *
     * @param GearmanClientFactory $clientFactory
     * @param GearmanWorkerFactory $workerFactory
     */
    public function __construct(GearmanClientFactory $clientFactory, GearmanWorkerFactory $workerFactory)
    {
        $this->clientFactory = $clientFactory;
        $this->workerFactory = $workerFactory;
        $this->setLogger(new NullLogger());
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

    public function getWorker(): \GearmanWorker
    {
        if (!$this->worker) {
            $this->worker = $this->workerFactory->getWorker();
        }
        return $this->worker;
    }

    /**
     * @return string
     */
    public function getBufferQueue(): string
    {
        return $this->clientFactory->getQueuePrefix() . '_eventBuffer';
    }

    /**
     * @return \Generator
     */
    public function subscribe(): \Generator
    {
        $this->getWorker()->addFunction(
            $this->getBufferQueue(),
            function (\GearmanJob $job, GearmanEventBuffer $object) {
                $data = $job->workload();
                if ($data = unserialize($data)) {
                    if ($data instanceof EventInterface) {
                        $object->push($data);
                    }
                }
            },
            $this
        );

        while ($this->getWorker()->work()) {
            if ($this->getWorker()->returnCode() != GEARMAN_SUCCESS) {
                $this->logger->error('Return code ' . $this->getWorker()->returnCode() . ' from gearman');
                break;
            }

            if (!$this->localBuffer) {
                yield from $this->localBuffer;
            }
        }
    }

    /**
     * @param EventInterface $event
     */
    protected function push(EventInterface $event)
    {
        $this->localBuffer[] = $event;
    }
}
