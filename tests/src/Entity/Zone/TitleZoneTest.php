<?php

namespace Noobus\GrootLib\Entity\Zone;

use PHPUnit\Framework\TestCase;

class TitleZoneTest extends TestCase
{
    public function testSerialization()
    {
        $zone = new TitleZone('masturdoor.com', 'demybus');
        $zone2 = new TitleZone('masturdoor.com', 'demybus');
        $serializedZone = serialize($zone);
        $unserializedZone = unserialize($serializedZone);
        $this->assertEquals($zone2, $unserializedZone);
    }
}
