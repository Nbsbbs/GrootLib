<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity;

interface ZoneInterface extends \Serializable
{
    public function getLanguage(): string;

    public function getDomain(): string;

    public function getGroup(): string;

    public function getType(): string;

    public function getAttributes(): array;

    public function getCategoryId(): ?int;

    public function getSearchKeyword(): string;

    public function getSearchKeywordTranslation(): string;

    public function getEmbedId(): int;

    public function getFixedSearchId(): int;

    public function isValidItemId(string $id): bool;
}
