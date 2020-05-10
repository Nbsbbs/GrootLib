<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity;

interface UserInterface
{
    public function getIp4(): string;
    public function getIp6(): string;
    public function getSessionId(): string;
    public function getUserAgent(): string;
    public function getTotalClicks(): int;
    public function getTotalViews(): int;
    public function sourceType(): string;
    public function sourceUrl(): string;
}
