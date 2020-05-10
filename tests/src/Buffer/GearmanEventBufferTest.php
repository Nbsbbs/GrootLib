<?php

namespace Noobus\GrootLib\Buffer;

use Noobus\GrootLib\Buffer\Gearman\GearmanFactory;
use Noobus\GrootLib\Entity\Config\GearmanConfig;
use Noobus\GrootLib\Entity\Event\EventType;
use Noobus\GrootLib\Entity\Event\ThumbEvent;
use Noobus\GrootLib\Entity\EventInterface;
use Noobus\GrootLib\Entity\Item\ThumbItem;
use Noobus\GrootLib\Entity\User\PresetUser;
use Noobus\GrootLib\Entity\Zone\CategoryZone;
use PHPUnit\Framework\TestCase;

class GearmanEventBufferTest extends TestCase
{
    public function getBuffer(): GearmanEventBuffer
    {
        $gearmanConfig = new GearmanConfig('213.152.180.23', 4730, 'groot');
        $cf = new GearmanFactory($gearmanConfig);
        $buffer = new GearmanEventBuffer($cf);
        return $buffer;
    }

    public function testCreateAndBufferEvent()
    {
        $buffer = $this->getBuffer();

        $thumb = new ThumbItem(1, 2);
        $zone = new CategoryZone('masturdoor.com', 125);
        $user = new PresetUser();

        $event = new ThumbEvent(
            $thumb,
            $zone,
            $user,
            EventType::TYPE_VIEW,
            0,
            new \DateTimeImmutable('now')
        );

        $buffer->buffer($event);

        $this->assertEquals(true, true);
    }

    public function testSubscribe()
    {
        foreach ($this->getBuffer()->subscribe() as $event) {
            $this->assertInstanceOf(EventInterface::class, $event);
            var_dump($event);
        }
    }
}
