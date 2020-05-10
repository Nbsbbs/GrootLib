<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\User;

use Noobus\GrootLib\Entity\UserInterface;

/**
 * Class PresetUser
 *
 * @package Noobus\GrootLib\Entity\User
 */
class PresetUser implements UserInterface
{
    /**
     * @var string
     */
    private $ip4;

    /**
     * @var string
     */
    private $ip6;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var int
     */
    private $totalClicks;

    /**
     * @var int
     */
    private $totalViews;

    /**
     * @var string
     */
    private $sourceType;

    /**
     * @var string
     */
    private $sourceUrl;

    /**
     * PresetUser constructor.
     *
     * @param string $ip4
     * @param string $ip6
     * @param string $sessionId
     * @param string $userAgent
     * @param int $totalClicks
     * @param int $totalViews
     * @param string $sourceType
     * @param string $sourceUrl
     */
    public function __construct(
        string $ip4,
        string $ip6,
        string $sessionId,
        string $userAgent,
        int $totalClicks,
        int $totalViews,
        string $sourceType,
        string $sourceUrl
    ) {
        $this->ip4 = $ip4;
        $this->ip6 = $ip6;
        $this->sessionId = $sessionId;
        $this->userAgent = $userAgent;
        $this->totalClicks = $totalClicks;
        $this->totalViews = $totalViews;
        $this->sourceType = $sourceType;
        $this->sourceUrl = $sourceUrl;
    }

    /**
     * @return string
     */
    public function getIp4(): string
    {
        return $this->ip4;
    }

    /**
     * @return string
     */
    public function getIp6(): string
    {
        return $this->ip6;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->sessionId;
    }

    /**
     * @return int
     */
    public function getTotalClicks(): int
    {
        return $this->totalClicks;
    }

    /**
     * @return int
     */
    public function getTotalViews(): int
    {
        return $this->totalViews;
    }

    /**
     * @return string
     */
    public function sourceType(): string
    {
        return $this->sourceType;
    }

    /**
     * @return string
     */
    public function sourceUrl(): string
    {
        return $this->sourceUrl;
    }
}
