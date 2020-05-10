<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\User;

use Noobus\GrootLib\Entity\UserInterface;

class User implements UserInterface
{
    public function getIp4(): string
    {
        return '192.232.127.27';
    }

    public function getIp6(): string
    {
        return '';
    }

    public function getSessionId(): string
    {
        return md5('sobaka');
    }

    public function getUserAgent(): string
    {
        return 'user-agent';
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
