<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\User;

use Noobus\GrootLib\Entity\UserInterface;

class SimpleSessionUser implements UserInterface
{
    private $ip4;
    private $ip6;
    private $sessionId;
    private $userAgent;

    public function __construct()
    {
        if ($ip6 = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->ip6 = $ip6;
            $this->ip4 = '';
        } elseif ($ip4 = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->ip6 = '';
            $this->ip4 = $ip4;
        }
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->sessionId = session_id();
    }

    public function getIp4(): string
    {
        return $this->ip4;
    }

    public function getIp6(): string
    {
        return $this->ip6;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function getTotalClicks(): int
    {
        return 0;
    }

    public function getTotalViews(): int
    {
        return 0;
    }

    public function sourceType(): string
    {
        return 'unknown';
    }

    public function sourceUrl(): string
    {
        return '';
    }
}
