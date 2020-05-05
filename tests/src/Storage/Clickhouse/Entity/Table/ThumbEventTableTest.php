<?php

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Table;

use PHPUnit\Framework\TestCase;

class ThumbEventTableTest extends TestCase
{
    public function testSqlCreate()
    {
        var_dump(ThumbEventTable::getSqlCreate());
    }
}
