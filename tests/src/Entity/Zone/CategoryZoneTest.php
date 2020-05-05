<?php

namespace Noobus\GrootLib\Entity\Zone;

use PHPUnit\Framework\TestCase;

class CategoryZoneTest extends TestCase
{
    public function testSerialization()
    {
        $zone = new CategoryZone('masturdoor.com', 125, 'en', 'demybus');
        $zone2 = new CategoryZone('masturdoor.com', 125, 'en', 'demybus');
        $serializedZone = serialize($zone);
        $unserializedZone = unserialize($serializedZone);
        $this->assertEquals($zone2, $unserializedZone);
    }
}
