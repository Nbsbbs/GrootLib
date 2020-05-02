<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity;

interface ItemInterface extends \Serializable
{
    public function getId(): string;
    public function getType(): string;
}
