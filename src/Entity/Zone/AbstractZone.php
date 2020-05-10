<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Zone;

abstract class AbstractZone implements \Serializable
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string|null
     */
    protected $group = '';

    /**
     * @var string
     */
    protected $type = ZoneType::TYPE_MIXED;

    /**
     * @var string
     */
    protected $language = 'en';


    /**
     * @param string $language
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
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
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->domain = $data['domain'];
        $this->group = $data['group'] ?? null;
        $this->language = $data['lang'] ?? 'en';
    }
}
