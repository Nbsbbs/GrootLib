<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage;

use Noobus\GrootLib\Buffer\EventBufferInterface;
use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

class ClickhouseStorage implements EventStorageInterface
{
    private $clientFactory;

    public function __construct(ClientFactory $factory)
    {
        $this->clientFactory = $factory;
    }

    public function save(EventInterface $event)
    {
       // TODO: save event to db
    }

}
