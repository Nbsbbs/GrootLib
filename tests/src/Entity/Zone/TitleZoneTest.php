<?php

namespace Noobus\GrootLib\Entity\Zone;

use PHPUnit\Framework\TestCase;

class TitleZoneTest extends TestCase
{
    public function testSerialization()
    {
        $zone = new TitleZone('masturdoor.com', 'en');
        $zone2 = new TitleZone('masturdoor.com', 'en');
        $serializedZone = serialize($zone);
        $unserializedZone = unserialize($serializedZone);
        $this->assertEquals($zone2, $unserializedZone);
    }
}
