<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Buffer;

use Noobus\GrootLib\Buffer\Gearman\GearmanFactory;
use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Buffer\EventBufferInterface;
use Noobus\GrootLib\Storage\EventStorageInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class GearmanEventBuffer implements EventBufferInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var GearmanFactory
     */
    private $gearmanFactory;

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
     * @param GearmanFactory $gearmanFactory
     */
    public function __construct(GearmanFactory $gearmanFactory)
    {
        $this->gearmanFactory = $gearmanFactory;
        $this->setLogger(new NullLogger());
    }

    /**
     * @param EventInterface $event
     */
    public function buffer(EventInterface $event)
    {
        $this->getClient()->doBackground($this->getBufferQueue(), serialize($event));
    }

    /**
     * @return \GearmanClient
     */
    public function getClient(): \GearmanClient
    {
        if (!$this->client) {
            $this->client = $this->gearmanFactory->getClient();
        }
        return $this->client;
    }

    public function getWorker(): \GearmanWorker
    {
        if (!$this->worker) {
            $this->worker = $this->gearmanFactory->getWorker();
        }
        return $this->worker;
    }

    /**
     * @return string
     */
    public function getBufferQueue(): string
    {
        return $this->gearmanFactory->getQueuePrefix() . '_eventBuffer';
    }

    /**
     * @param EventStorageInterface $storage
     * @param int $timeout
     */
    public function subscribe(EventStorageInterface $storage, int $timeout = 600): void
    {
        $startTime = time();
        $this->getWorker()->addFunction(
            $this->getBufferQueue(),
            function (\GearmanJob $job, EventStorageInterface $storage) {
                $data = $job->workload();
                $this->logger->debug('Serialized event: '.$data);
                if ($data = unserialize($data)) {
                    if ($data instanceof EventInterface) {
                        $storage->save($data);
                    } else {
                        $this->logger->warning(sprintf('Unsupported event type %s received', get_class($data)));
                    }
                }
            },
            $storage
        );

        while ($this->getWorker()->work()) {
            if ($this->getWorker()->returnCode() != GEARMAN_SUCCESS) {
                $this->logger->error('Return code ' . $this->getWorker()->returnCode() . ' from gearman');
                break;
            }

            $runningTime = time() - $startTime;
            if ($runningTime > $timeout) {
                return;
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
