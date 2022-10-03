<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Config;

class ClickhouseConfig
{
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
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private int $timeout = 5;

    /**
     * @var string
     */
    private $port;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * ClickhouseConfiguration constructor.
     *
     * @param string $host
     * @param string $port
     * @param string $database
     * @param string $username
     * @param string $password
     */
    public function __construct(
        string $host,
        string $port,
        string $database = 'default',
        string $username = 'default',
        string $password = ''
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->password = $password;
        $this->username = $username;
    }
}
