<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Table;

use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\DateTimeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\EventTypeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\EventZonePlaceIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemGalleryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemThumbIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserIp4Field;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserIp6Field;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserSessionIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserUserAgentField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneCategoryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneEmbedIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneLanguageField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneSearchKeywordField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneTypeField;

class ThumbEventTable
{
    protected const NAME = 'stat_thumb_events';
    protected const FIELDS = [
        DateTimeField::class,
        EventTypeField::class,
        //
        EventZonePlaceIdField::class,
        //
        UserIp4Field::class,
        UserIp6Field::class,
        UserSessionIdField::class,
        UserUserAgentField::class,
        //
        ZoneTypeField::class,
        ZoneCategoryIdField::class,
        ZoneLanguageField::class,
        ZoneDomainField::class,
        ZoneSearchKeywordField::class,
        ZoneEmbedIdField::class,
        ZoneGroupField::class,
        //
        ItemThumbIdField::class,
        ItemGalleryIdField::class,
    ];
    protected const KEY_FIELDS = [
        'ZoneGroup',
        'ZoneDomain',
        'EventDateTime',
        'ZoneCategoryId',
        'sipHash64(UserSessionId)',
    ];

    public static function getName(): string
    {
        return self::NAME;
    }

    public static function getSqlCreate(): string
    {
        $text = sprintf(
            'CREATE TABLE %s (%s) ENGINE = MergeTree PARTITION BY toYYYYMM(%s) ORDER BY (%s) SAMPLE BY sipHash64(%s)',
            self::NAME,
            self::getSqlFields(),
            DateTimeField::name(),
            implode(', ', self::KEY_FIELDS),
            UserSessionIdField::name()
        );

        return $text;
    }

    public static function getSqlFields(): string
    {
        $collection = [];
        foreach (self::FIELDS as $fieldClass) {
            $collection[] = $fieldClass::toSql();
        }
        return implode(', ', $collection);
    }
}
