<?php

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Field;

use PHPUnit\Framework\TestCase;

class UserIp4FieldTest extends TestCase
{
    public function testValue()
    {
        $ipv4 = '192.232.127.27';
        $field = new UserIp4Field($ipv4);
        $this->assertEquals($ipv4, $field->value());
        $this->assertEquals(true, $field->isValid());
    }
}
