<?php

use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Statistics\Request\SearchResultStatRequest;
use Noobus\GrootLib\Statistics\Service\SearchResultStatService;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

require_once '../bootstrap.php';

$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);

$service = new SearchResultStatService($clientFactory);

$request = new SearchResultStatRequest('demybus', 'teentubefuck.com', 'real penetration sex scenes in mainstream movies');
$request->setIgnoreDomain(true);

$response = $service->getStats($request);


if ($response->getTotalItems() > 0) {
    foreach ($response->walkItems() as $index => $thumbnailStat) {
        echo sprintf('%d. %d %d/%d=%.4f%s', $index, $thumbnailStat->getId(), $thumbnailStat->getClicks(), $thumbnailStat->getViews(), $thumbnailStat->getCtr(), PHP_EOL);
    }
}

