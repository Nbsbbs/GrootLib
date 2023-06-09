<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

use Nbsbbs\Common\Orientation\OrientationInterface;
use Nbsbbs\Common\Orientation\Straight;
use Noobus\GrootLib\Entity\ZoneInterface;

abstract class AbstractZone implements \Serializable, ZoneInterface
{
    /**
     * @var string
     */
    protected string $domain;

    /**
     * @var string
     */
    protected string $group = '';

    /**
     * @var string
     */
    protected string $type = ZoneType::TYPE_MIXED;

    /**
     * @var string
     */
    protected string $language = 'en';

    /**
     * @var OrientationInterface|null
     */
    protected ?OrientationInterface $orientation = null;

    /**
     * @param string $language
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
    }

    /**
     * @param OrientationInterface $orientation
     * @return void
     */
    public function setOrientation(OrientationInterface $orientation): void
    {
        $this->orientation = $orientation;
    }

    /**
     * @return OrientationInterface
     */
    public function getOrientation(): OrientationInterface
    {
        if (is_null($this->orientation)) {
            return new Straight();
        } else {
            return $this->orientation;
        }
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return string|null
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [];
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
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $serialized)
    {
        $data = unserialize($serialized);
        $this->domain = $data['domain'];
        $this->group = $data['group'] ?? '';
        $this->language = $data['lang'] ?? 'en';
    }

    /**
     * @return int
     */
    public function getFixedSearchId(): int
    {
        return 0;
    }
}
