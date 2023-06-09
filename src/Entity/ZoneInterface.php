<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity;

use Nbsbbs\Common\Orientation\OrientationInterface;

interface ZoneInterface extends \Serializable
{
    /**
     * @return string
     */
    public function getLanguage(): string;

    /**
     * @return string
     */
    public function getDomain(): string;

    /**
     * @return string
     */
    public function getGroup(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int;

    /**
     * @return string
     */
    public function getSearchKeyword(): string;

    /**
     * @return string
     */
    public function getSearchKeywordTranslation(): string;

    /**
     * @return int
     */
    public function getEmbedId(): int;

    /**
     * @return int
     */
    public function getFixedSearchId(): int;

    /**
     * @return OrientationInterface
     */
    public function getOrientation(): OrientationInterface;

    /**
     * @param string $id
     * @return bool
     */
    public function isValidItemId(string $id): bool;


}
