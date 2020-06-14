<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage;

use Noobus\GrootLib\Buffer\EventBufferInterface;
use Noobus\GrootLib\Entity\Event\ThumbEvent;
use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Entity\Item\ItemType;
use Noobus\GrootLib\Entity\Event\EventType;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\RowInterface;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Table\ThumbEventTable;
use Noobus\GrootLib\Storage\Clickhouse\Entity\TableInterface;

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
        $client = $this->clientFactory->getClient();
        $table = $this->getTableObject($event);
        $row = $table->createRow($event);
        $client->insert(
            $table::getBufferName(),
            [
                array_values($row),
            ],
            array_keys($row)
        );
    }

    /**
     * @param EventInterface $event
     * @return TableInterface
     */
    protected function getTableObject(EventInterface $event): TableInterface
    {
        $this->validateEvent($event);
        if ($event instanceof ThumbEvent) {
            return new ThumbEventTable();
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
