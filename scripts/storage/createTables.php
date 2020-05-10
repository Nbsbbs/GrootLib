<?php

use Noobus\GrootLib\Storage\Clickhouse\Entity\Table\ThumbEventTable;

require_once '../bootstrap.php';

echo ThumbEventTable::getSqlCreate().PHP_EOL;
