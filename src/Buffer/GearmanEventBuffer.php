<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Buffer;

use Noobus\GrootLib\Buffer\Gearman\GearmanFactory;
use Noobus\GrootLib\Entity\EventInterface;
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
    private GearmanFactory $gearmanFactory;

    /**
     * @var \GearmanClient
     */
    private ?\GearmanClient $client = null;

    /**
     * @var \GearmanWorker
     */
    private ?\GearmanWorker $worker = null;

    /**
     * @var array
     */
    private array $localBuffer = [];

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
     * @param EventStorageInterface $eventStorage
     * @param int $timeout
     */
    public function subscribe(EventStorageInterface $eventStorage, int $timeout = 600): void
    {
        $startTime = time();
        $this->getWorker()->addFunction(
            $this->getBufferQueue(),
            function (\GearmanJob $job, EventStorageInterface $eventStorage) {
                $data = $job->workload();
                $this->logger->debug('Serialized event: ' . $data);
                try {
                    if ($data = unserialize($data)) {
                        if ($data instanceof EventInterface) {
                            try {
                                $eventStorage->save($data);
                                return true;
                            } catch (\TypeError $error) {
                                $this->logger->error($error->getMessage());
                            }
                        } else {
                            $this->logger->warning(sprintf('Unsupported event type %s received', get_class($data)));
                        }
                    } else {
                        $this->logger->warning(sprintf('Cannot unserialize event: %s', $data));
                    }
                } catch (\TypeError $error) {
                    $this->logger->warning(sprintf('Bad serialized event: %s', $error->getMessage()));
                }
                return false;
            },
            $eventStorage
        );

        while ($this->getWorker()->work()) {
            if ($this->getWorker()->returnCode() != GEARMAN_SUCCESS) {
                $this->logger->error('Return code ' . $this->getWorker()->returnCode() . ' from gearman');
                return;
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
