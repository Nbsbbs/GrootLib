<?php

use Noobus\GrootLib\Storage\Clickhouse\Entity\Table\RotationEventTable;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Table\ThumbEventTable;

require_once '../bootstrap.php';

echo ThumbEventTable::getSqlCreate().PHP_EOL;
echo RotationEventTable::getSqlCreate().PHP_EOL;

// CREATE MATERIALIZED VIEW stat_rotation_events_places ENGINE=AggregatingMergeTree() PARTITION BY toYYYYMM(EventDate) ORDER BY (Group, Domain, PlaceId, EventDate) POPULATE AS SELECT ZoneGroup as Group, ZoneDomain as Domain, EventZonePlaceId as PlaceId, sumIf(1,EventType='view') AS Views, sumIf(1,EventType='click') AS Clicks, if (Views>0, Clicks/Views, 0) AS Ctr, toDate(EventDateTime) as EventDate FROM stat_rotation_events GROUP BY ZoneGroup, ZoneDomain, EventZonePlaceId, EventDate;
