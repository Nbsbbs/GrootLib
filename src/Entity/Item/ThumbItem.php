<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Entity\Item;

use Noobus\GrootLib\Entity\ItemInterface;

class ThumbItem implements ItemInterface
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return ItemType::TYPE_THUMB;
    }
}
