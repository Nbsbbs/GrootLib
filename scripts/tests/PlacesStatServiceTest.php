<?php

use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Statistics\Clickhouse\Places\ThumbEventPlacesStatService;
use Noobus\GrootLib\Statistics\Request\PlacesStatRequest;
use Noobus\GrootLib\Statistics\Service\PlacesStatServiceFacade;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

require_once '../bootstrap.php';

$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);

$concretePlacesStatService = new ThumbEventPlacesStatService($clientFactory);

$placesStatService = new PlacesStatServiceFacade($concretePlacesStatService);

$request = (new PlacesStatRequest('noobus', 'tubemilfmom.xyz'))->withLimit(30)->withMinClicks(1)->withMaxClicks(8);

$response = $placesStatService->getStats($request);

if ($response->getTotalPlaces() > 0) {
    foreach($response->walkItems() as $placementStat) {
        var_dump($placementStat);
    }
}

