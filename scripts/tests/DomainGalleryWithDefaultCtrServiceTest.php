<?php

use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Statistics\Request\DomainGalleriesWithDefaultCtrRequest;
use Noobus\GrootLib\Statistics\Request\Item\IdWithDefaultCtr;
use Noobus\GrootLib\Statistics\Service\DomainGalleryWithDefaultCtrService;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

require_once '../bootstrap.php';

$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);

$service = new DomainGalleryWithDefaultCtrService($clientFactory);

$request = new DomainGalleriesWithDefaultCtrRequest('demybus', 'hottmovs.com');
$request->pushItem(new IdWithDefaultCtr(1, 0.5));
$request->pushItem(new IdWithDefaultCtr(2, 1.5));
$request->pushItem(new IdWithDefaultCtr(3, 2.5));
$request->pushItem(new IdWithDefaultCtr(4, 3.5));
$request->withLimitOffset(100, 0);
$response = $service->getStats($request);

foreach ($response->walkItems() as $index => $thumbnailStat) {
    echo sprintf('%d. %d %d/%d=%.4f%s', $index, $thumbnailStat->getId(), $thumbnailStat->getClicks(), $thumbnailStat->getViews(), $thumbnailStat->getCtr(), PHP_EOL);
}



