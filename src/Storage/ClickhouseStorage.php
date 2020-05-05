<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage;

use Noobus\GrootLib\Buffer\EventBufferInterface;
use Noobus\GrootLib\Entity\Event\ThumbEvent;
use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Entity\Item\ItemType;
use Noobus\GrootLib\Entity\Event\EventType;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\AbstractJsonRow;
use Noobus\GrootLib\Storage\Clickhouse\Entity\RowInterface;

class ClickhouseStorage implements EventStorageInterface
{
    private $clientFactory;

    public function __construct(ClientFactory $factory)
    {
        $this->clientFactory = $factory;
    }

    /**
     * @param EventInterface $event
     */
    public function save(EventInterface $event)
    {
        $tableName = $this->getTableName($event);
        $rowStructure = $this->getRowObject($event);
        foreach ($event->getMetrics() as $metric => $value) {
            $rowStructure->set($metric, $value);
        }
    }

    /**
     * @param EventInterface $event
     * @return string
     */
    protected function getTableName(EventInterface $event): string
    {
        $this->validateEvent($event);
        if ($event instanceof ThumbEvent) {
            return 'stat_thumb';
        }

        throw new \InvalidArgumentException('Unknown event type "' . get_class($event) . '"');
    }

    /**
     * @param EventInterface $event
     */
    protected function validateEvent(EventInterface $event)
    {
        if (!EventType::isValidType($event->getType())) {
            throw new \InvalidArgumentException('Invalid event type "' . $event->getType() . '"');
        }

        if (!ItemType::isValidType($event->getItem()->getType())) {
            throw new \InvalidArgumentException('Invalid item type "' . $event->getType() . '"');
        }
    }

    /**
     * @param EventInterface $event
     * @return RowInterface
     */
    protected function getRowObject(EventInterface $event): RowInterface
    {
        $this->validateEvent($event);
    }
}
