<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity;

use Noobus\GrootLib\Entity\Zone\ZoneType;

interface ZoneInterface extends \Serializable
{
    public function getLanguage(): string;
    public function getDomain(): string;
    public function getGroup(): string;
    public function getType(): string;
    public function getAttributes(): array;
    public function getCategoryId(): ?int;
    public function getSearchKeyword(): string;
    public function getEmbedId(): int;

    public function isValidItemId(string $id): bool;
}
