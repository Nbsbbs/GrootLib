<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity;

use Noobus\GrootLib\Entity\Zone\ZoneType;

interface ZoneInterface extends \Serializable
{
    public function getId(): string;
    public function getDomain(): string;
    public function getParentId(): ?string;
    public function getGroup(): ?string;
    public function getType(): string;
}
