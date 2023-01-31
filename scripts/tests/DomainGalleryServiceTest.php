<?php

use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Statistics\Request\DomainGalleriesRequest;
use Noobus\GrootLib\Statistics\Service\DomainGalleryStatService;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

require_once '../bootstrap.php';

$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);

$service = new DomainGalleryStatService($clientFactory);

$request = new DomainGalleriesRequest('thesexchat', 'hadesex.com', [23778, 72935, 61631, 59874, 61632]);
$response = $service->getStats($request);

foreach ($response->walkItems() as $index => $thumbnailStat) {
    echo sprintf('%d. %d %d/%d=%.4f%s', $index, $thumbnailStat->getId(), $thumbnailStat->getClicks(), $thumbnailStat->getViews(), $thumbnailStat->getCtr(), PHP_EOL);
}

echo 'Time: ' . $response->getElapsedTime() . PHP_EOL;



