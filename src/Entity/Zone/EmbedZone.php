<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

use Nbsbbs\Common\Orientation\OrientationFactory;
use Nbsbbs\Common\Orientation\OrientationInterface;
use Noobus\GrootLib\Entity\ZoneInterface;
use Noobus\GrootLib\Entity\Zone\ItemValidation\ZoneOfNumericItemsTrait;

/**
 * Class EmbedZone
 *
 * @package Noobus\GrootLib\Entity\Zone
 */
class EmbedZone extends AbstractZone implements ZoneInterface
{
    use ZoneOfNumericItemsTrait;
    /**
     * @var string
     */
    protected string $type = ZoneType::TYPE_EMBED;

    /**
     * @var int
     */
    protected int $embedId;

    /**
     * CategoryZone constructor.
     *
     * @param string $domain
     * @param string $language
     * @param string $group
     */
    public function __construct(int $embedId, string $domain, string $language = 'en', string $group = '')
    {
        $this->domain = $domain;
        $this->language = $language;
        $this->group = $group;
        $this->embedId = $embedId;
    }

    public function getCategoryId(): ?int
    {
        return 0;
    }

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
        return $this->embedId;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            'domain' => $this->domain,
            'group' => $this->group,
            'lang' => $this->language,
            'embedId' => $this->embedId,
            'orientation' => $this->orientation->getCode(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->domain = $data['domain'];
        $this->group = $data['group'] ?? '';
        $this->language = $data['lang'] ?? 'en';
        $this->embedId = $data['embedId'];
        $this->orientation = OrientationFactory::make($data['orientation'] ?? OrientationInterface::STRAIGHT);
    }
}
