<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Config;

/**
 * Class GearmanConfig
 *
 * @package Noobus\GrootLib\Entity\Config
 */
class GearmanConfig
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $port;

    /**
     * @var string
     */
    private $prefix;

    /**
     * GearmanConfig constructor.
     *
     * @param string $host
     * @param string $port
     * @param string $prefix
     */
    public function __construct(string $host, string $port, string $prefix)
    {
        $this->host = $host;
        $this->port = $port;
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
