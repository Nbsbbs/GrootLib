<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage;

use Noobus\GrootLib\Entity\Event\EventType;
use Noobus\GrootLib\Entity\Event\RotationEvent;
use Noobus\GrootLib\Entity\Event\ThumbEvent;
use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Entity\Item\ItemType;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Table\RotationEventTable;
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
        try {
            $client = $this->clientFactory->getClient();
            $table = $this->getTableObject($event);
            if (($event instanceof ThumbEvent) and ($table instanceof ThumbEventTable)) {
                $row = $table->createRow($event);
                $client->insert(
                    $table::getBufferName(),
                    [
                        array_values($row),
                    ],
                    array_keys($row)
                );
            } elseif (($event instanceof RotationEvent) and ($table instanceof RotationEventTable)) {
                $row = $table->createRow($event);
                $client->insert(
                    $table::getBufferName(),
                    [
                        array_values($row),
                    ],
                    array_keys($row)
                );
            } else {
                throw new \InvalidArgumentException('event must be instance of ThumbEvent to be processed by ThumbEventTable');
            }
        } catch (\Throwable $e) {
            throw new \RuntimeException('Storage save error: '.$e->getMessage());
        }
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
        } elseif ($event instanceof RotationEvent) {
            return new RotationEventTable();
        }

        throw new \InvalidArgumentException('Unknown event type "' . get_class($event) . '"');
    }

    /**
     * @param EventInterface $event
     */
    protected function validateEvent(EventInterface $event)
    {
        if (!EventType::isValidType($event->getEventType())) {
            throw new \InvalidArgumentException('Invalid event type "' . $event->getEventType() . '"');
        }

        if (!ItemType::isValidType($event->getItem()->getType())) {
            throw new \InvalidArgumentException('Invalid item type "' . $event->getEventType() . '"');
        }
    }
}
