<?php

use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Statistics\Request\DomainSearchStatRequest;
use Noobus\GrootLib\Statistics\Request\SearchResultStatRequest;
use Noobus\GrootLib\Statistics\Service\DomainSearchStatService;
use Noobus\GrootLib\Statistics\Service\SearchResultStatService;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

require_once '../bootstrap.php';

$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);

$service = new DomainSearchStatService($clientFactory);

$request = new DomainSearchStatRequest('demybus', 'thai ladyboy');
$request->withLimitOffset(10, 0);
$response = $service->getStats($request);
var_dump($response->getTotalRows());
var_dump($response->getElapsedTime());

var_dump($response->getItemsCount());

