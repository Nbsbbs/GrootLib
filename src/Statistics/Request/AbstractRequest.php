<?php

namespace Noobus\GrootLib\Statistics\Request;

abstract class AbstractRequest
{
    /**
     * @var int
     */
    protected int $limit = 100;

    /**
     * @var int
     */
    protected int $offset = 0;

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function withLimitOffset(int $limit, int $offset): self
    {
        $this->validateLimit($limit);
        $this->validateOffset($offset);
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param int $limit
     */
    protected function validateLimit(int $limit): void
    {
        if ($limit < 1) {
            throw new \InvalidArgumentException('Limit must be positive integer');
        }
    }

    /**
     * @param int $offset
     */
    protected function validateOffset(int $offset): void
    {
        if ($offset < 0) {
            throw new \InvalidArgumentException('Offset must be non-negative integer');
        }
    }
}
