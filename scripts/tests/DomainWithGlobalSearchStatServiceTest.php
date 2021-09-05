<?php

use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Statistics\Request\DomainSearchStatRequest;
use Noobus\GrootLib\Statistics\Request\SearchResultStatRequest;
use Noobus\GrootLib\Statistics\Service\DomainSearchStatService;
use Noobus\GrootLib\Statistics\Service\DomainWithGlobalSearchStatService;
use Noobus\GrootLib\Statistics\Service\SearchResultStatService;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

require_once '../bootstrap.php';

$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);

$service = new DomainWithGlobalSearchStatService($clientFactory);

$request = new DomainSearchStatRequest('demybus', 'green hair', 'hottmovs.com');
$request->withLimitOffset(100, 0);
$response = $service->getStats($request);

var_dump($response->getElapsedTime());
var_dump($response->getItemsCount());
var_dump($response->getTotalRows());

foreach ($response->walkItems() as $item) {
    echo $item->getGalleryId().":".$item->getThumbnailId().' => '.$item->getCtr().PHP_EOL;
}
