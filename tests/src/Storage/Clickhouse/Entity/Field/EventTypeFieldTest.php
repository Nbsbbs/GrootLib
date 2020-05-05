<?php

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

use Noobus\GrootLib\Entity\Event\EventType;
use PHPUnit\Framework\TestCase;

class EventTypeFieldTest extends TestCase
{
    public function testToSql()
    {
        $field = new EventTypeField(EventType::TYPE_CLICK);
        $expected = "`EventType` Enum('view' = 1, 'click' = 2, 'bounce' = 3, 'complaint' = 4)";
        $this->assertEquals($expected, $field->toSql());
    }
}
