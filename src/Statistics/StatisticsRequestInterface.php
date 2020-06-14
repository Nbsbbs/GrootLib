<?php

namespace Noobus\GrootLib\Statistics;

interface StatisticsRequestInterface
{
    public function setRequestType(string $type);
    public function getRequestType(): string;
}
