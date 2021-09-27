<?php

use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Statistics\Request\GallerySearchResultStatRequest;
use Noobus\GrootLib\Statistics\Service\GallerySearchResultStatService;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

require_once '../bootstrap.php';

$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);

$service = new GallerySearchResultStatService($clientFactory);
$ids = [
    0 => 3268067,
    1 => 2581517,
    2 => 3070565,
    3 => 3273061,
    4 => 3337792,
    5 => 3594869,
    6 => 2539042,
    7 => 3031605,
    8 => 3315299,
    9 => 3336455,
    10 => 3323921,
    11 => 3338908,
    12 => 3342669,
    13 => 3342680,
    14 => 3350661,
    15 => 3356243,
    16 => 3384640,
    17 => 3398345,
    18 => 3473973,
    19 => 3530991,
    20 => 3563219,
    21 => 3585843,
    22 => 3608942,
    23 => 3308849,
    24 => 3335178,
    25 => 3349498,
    26 => 3349992,
    27 => 3350054,
    28 => 3350109,
    29 => 3352233,
    30 => 3373631,
    31 => 3378944,
    32 => 3412369,
    33 => 3432977,
    34 => 3465888,
    35 => 3518542,
    36 => 3532416,
    37 => 3560992,
    38 => 3561342,
    39 => 3596937,
    40 => 3602939,
];

$request = new GallerySearchResultStatRequest('demybus', 'hottmovs.com', 'flasher', $ids);
$request->withLimitOffset(100, 0);
$response = $service->getStats($request);

foreach ($response->walkItems() as $index => $thumbnailStat) {
    echo sprintf('%d. %d %d/%d=%.4f%s', $index, $thumbnailStat->getId(), $thumbnailStat->getClicks(), $thumbnailStat->getViews(), $thumbnailStat->getCtr(), PHP_EOL);
}

$query = 'flasher';


