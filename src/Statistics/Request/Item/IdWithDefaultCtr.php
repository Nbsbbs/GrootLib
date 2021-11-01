<?php

namespace Noobus\GrootLib\Statistics\Request\Item;

class IdWithDefaultCtr
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var float
     */
    private float $ctr;

    public function __construct(int $id, float $ctr)
    {
        $this->id = $id;
        $this->ctr = $ctr;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getCtr(): float
    {
        return $this->ctr;
    }
}
