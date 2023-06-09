<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

use Nbsbbs\Common\Orientation\OrientationFactory;
use Nbsbbs\Common\Orientation\OrientationInterface;
use Noobus\GrootLib\Entity\Zone\ItemValidation\ZoneOfNumericItemsTrait;
use Noobus\GrootLib\Entity\ZoneInterface;

/**
 * Class CategoryZone
 *
 * @package Noobus\GrootLib\Entity\Zone
 */
class FixedTopQueryZone extends AbstractZone implements ZoneInterface
{
    use ZoneOfNumericItemsTrait;

    /**
     * @var string
     */
    protected string $type = ZoneType::TYPE_FIXED_TOP_QUERY;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param string $language
     * @param string $group
     */
    public function __construct(
        string $domain,
        string $language = 'en',
        string $group = ''
    ) {
        $this->domain = $domain;
        $this->group = $group;
        $this->language = $language;
    }

    /**
     * @return int
     */
    public function getCategoryId(): ?int
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            'd' => $this->domain,
            'g' => $this->group,
            'l' => $this->language,
            'o' => $this->orientation->getCode(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $serialized)
    {
        $data = unserialize($serialized);
        $this->domain = $data['d'];
        $this->group = $data['g'] ?? '';
        $this->language = $data['l'] ?? 'en';
        $this->orientation = OrientationFactory::make($data['o'] ?? OrientationInterface::STRAIGHT);
    }

    /**
     * @return string
     */
    public function getSearchKeyword(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getSearchKeywordTranslation(): string
    {
        return '';
    }

    /**
     * @return int
     */
    public function getEmbedId(): int
    {
        return 0;
    }
}
