<?php

use Noobus\GrootLib\Buffer\Gearman\GearmanFactory;
use Noobus\GrootLib\Buffer\GearmanEventBuffer;
use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Entity\Config\GearmanConfig;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\ClickhouseStorage;

require_once '../bootstrap.php';

$gearmanConfig = new GearmanConfig('213.152.180.23', 4730, 'groot');
$cf = new GearmanFactory($gearmanConfig);
$buffer = new GearmanEventBuffer($cf);
$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);
$storage = new ClickhouseStorage($clientFactory);

$buffer->subscribe($storage);
