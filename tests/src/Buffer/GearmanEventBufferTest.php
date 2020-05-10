<?php

namespace Noobus\GrootLib\Buffer;

use Noobus\GrootLib\Buffer\Gearman\GearmanClientFactory;
use Noobus\GrootLib\Buffer\Gearman\GearmanWorkerFactory;
use Noobus\GrootLib\Entity\Config\GearmanConfig;
use Noobus\GrootLib\Entity\Event\EventType;
use Noobus\GrootLib\Entity\Event\ThumbEvent;
use Noobus\GrootLib\Entity\Item\ThumbItem;
use Noobus\GrootLib\Entity\User\User;
use Noobus\GrootLib\Entity\Zone\CategoryZone;
use PHPUnit\Framework\TestCase;

class GearmanEventBufferTest extends TestCase
{
    public function testSubscribe()
    {
        $gearmanConfig = new GearmanConfig('213.152.180.23', '4730', 'groot');
        $wf = new GearmanWorkerFactory($gearmanConfig);
        $cf = new GearmanClientFactory($gearmanConfig);

        $buffer = new GearmanEventBuffer($cf, $wf);

        $thumb = new ThumbItem(1, 2);
        $zone = new CategoryZone('masturdoor.com', 125);
        $user = new User();

        $event = new ThumbEvent(
            $thumb,
            $zone,
            $user,
            EventType::TYPE_VIEW,
            0,
            new \DateTimeImmutable('now')
        );

        $buffer->buffer($event);
    }
}
