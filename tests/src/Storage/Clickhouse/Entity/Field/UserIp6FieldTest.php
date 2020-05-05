<?php

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

use PHPUnit\Framework\TestCase;

class UserIp6FieldTest extends TestCase
{
    public function testValue()
    {
        $ipv6 = '2001:db8:0:0:0:0:2:1';
        $field = new UserIp6Field($ipv6);
        $this->assertEquals($ipv6, $field->value());
        $this->assertEquals(true, $field->isValid());
    }
}
