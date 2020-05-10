<?php

use Noobus\GrootLib\Buffer\Gearman\GearmanFactory;
use Noobus\GrootLib\Buffer\GearmanEventBuffer;
use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Entity\Config\GearmanConfig;
use Noobus\GrootLib\Entity\Event\EventType;
use Noobus\GrootLib\Entity\Event\ThumbEvent;
use Noobus\GrootLib\Entity\Item\ThumbItem;
use Noobus\GrootLib\Entity\User\User;
use Noobus\GrootLib\Entity\Zone\CategoryZone;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\ClickhouseStorage;

require_once '../bootstrap.php';

$gearmanConfig = new GearmanConfig('213.152.180.23', 4730, 'groot');
$cf = new GearmanFactory($gearmanConfig);
$buffer = new GearmanEventBuffer($cf);

$thumb = new ThumbItem(1, 2);
$zone = new CategoryZone('masturdoor.com', 125);
$user = new User();

$event = new ThumbEvent(
    $thumb,
    $zone,
    $user,
    EventType::TYPE_VIEW,
    0,
    new \DateTimeImmutable('now')
);
$buffer->buffer($event);

$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');

$clientFactory = new ClientFactory($config);

$storage = new ClickhouseStorage($clientFactory);

$buffer->subscribe($storage);
