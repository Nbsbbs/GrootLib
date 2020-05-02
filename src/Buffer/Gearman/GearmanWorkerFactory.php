<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Buffer\Gearman;

use Noobus\GrootLib\Entity\Config\GearmanConfig;

/**
 * Class GearmanWorkerFactory
 *
 * @package Noobus\GrootLib\Buffer\Gearman
 */
class GearmanWorkerFactory
{
    /**
     * @var GearmanConfig
     */
    private $config;

    /**
     * GearmanClientFactory constructor.
     *
     * @param GearmanConfig $config
     */
    public function __construct(GearmanConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \GearmanWorker
     */
    public function getWorker(): \GearmanWorker
    {
        $client = new \GearmanWorker();
        $client->addServer(
            $this->config->getHost(),
            $this->config->getPort()
        );

        return $client;
    }

    /**
     * @return string
     */
    public function getQueuePrefix(): string
    {
        return $this->config->getPrefix();
    }
}
