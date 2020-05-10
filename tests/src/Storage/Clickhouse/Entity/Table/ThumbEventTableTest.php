<?php

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Table;

use Noobus\GrootLib\Entity\Event\EventType;
use Noobus\GrootLib\Entity\Event\ThumbEvent;
use Noobus\GrootLib\Entity\Item\ThumbItem;
use Noobus\GrootLib\Entity\User\PresetUser;
use Noobus\GrootLib\Entity\Zone\CategoryZone;
use PHPUnit\Framework\TestCase;

class ThumbEventTableTest extends TestCase
{
    public function testSqlCreate()
    {
        var_dump(ThumbEventTable::getSqlCreate());
    }

    public function testCreateRow()
    {
        $thumb = new ThumbItem(1, 1);
        $zone = new CategoryZone('masturdoor.com', 17, 'en', 'demybus');

        $user = new PresetUser();
        $type = EventType::TYPE_CLICK;
        $zonePlaceId = 1;
        $timestamp = new \DateTimeImmutable();
        $event = new ThumbEvent($thumb, $zone, $user, $type, $zonePlaceId, $timestamp);
        var_dump(serialize($event));
        $table = new ThumbEventTable();
        var_dump($table->createRow($event));
    }
}
