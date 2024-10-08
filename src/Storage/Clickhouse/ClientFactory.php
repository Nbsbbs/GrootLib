<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse;

use Noobus\GrootLib\Entity\Config\ClickhouseConfig;

use ClickHouseDB\Client;

class ClientFactory
{
    private $config;

    /**
     * ClientFactory constructor.
     *
     * @param ClickhouseConfig $config
     */
    public function __construct(ClickhouseConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param int $timeout
     * @return void
     */
    public function setTimeout(int $timeout): void
    {
        $this->config->setTimeout($timeout);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        $client = new Client([
            'host' => $this->config->getHost(),
            'port' => $this->config->getPort(),
            'username' => $this->config->getUsername(),
            'password' => $this->config->getPassword(),
        ]);
        $client->database($this->config->getDatabase());
        $client->enableLogQueries(false);
        $client->setTimeout($this->config->getTimeout());
        $client->setConnectTimeOut((int) $this->config->getTimeout());

        return $client;
    }
}
