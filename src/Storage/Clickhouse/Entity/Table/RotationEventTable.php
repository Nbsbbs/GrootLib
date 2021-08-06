<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity\Table;

use Noobus\GrootLib\Entity\Event\RotationEvent;
use Noobus\GrootLib\Entity\Event\ThumbEvent;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\DateTimeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\EventTypeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\EventZonePlaceIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemGalleryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemRotationIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemThumbIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserClickNumberField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserIp4Field;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserIp6Field;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserSessionIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserSourceTypeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserSourceUrlField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserUserAgentField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneCategoryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneEmbedIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneLanguageField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneSearchKeywordField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneSearchKeywordTranslationField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneTypeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\TableInterface;

class RotationEventTable implements TableInterface
{
    protected const NAME = 'stat_rotation_events';
    protected const BUFFER_NAME = 'stat_rotation_events_buffer';
    protected const FIELDS = [
        DateTimeField::class,
        EventTypeField::class,
        //
        EventZonePlaceIdField::class,
        ItemRotationIdField::class,
        //
        UserIp4Field::class,
        UserIp6Field::class,
        UserSessionIdField::class,
        UserUserAgentField::class,
        UserSourceTypeField::class,
        UserSourceUrlField::class,
        UserClickNumberField::class,
        //
        ZoneTypeField::class,
        ZoneCategoryIdField::class,
        ZoneLanguageField::class,
        ZoneDomainField::class,
        ZoneSearchKeywordField::class,
        ZoneSearchKeywordTranslationField::class,
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

    public function createRow(RotationEvent $event): array
    {
        $row[DateTimeField::name()] = (new DateTimeField($event->getTimestamp()))->value();
        $row[EventTypeField::name()] = (new EventTypeField($event->getEventType()))->value();
        $row[EventZonePlaceIdField::name()] = (new EventZonePlaceIdField($event->getPagePlaceId()))->value();
        $row[ItemRotationIdField::name()] = (new ItemRotationIdField($event->getRotationId()))->value();
        $row[UserIp4Field::name()] = (new UserIp4Field($event->getUser()->getIp4()))->value();
        $row[UserIp6Field::name()] = (new UserIp6Field($event->getUser()->getIp6()))->value();
        $row[UserSessionIdField::name()] = (new UserSessionIdField($event->getUser()->getSessionId()))->value();
        $row[UserUserAgentField::name()] = (new UserUserAgentField($event->getUser()->getUserAgent()))->value();
        $row[UserSourceTypeField::name()] = (new UserSourceTypeField($event->getUser()->sourceType()))->value();
        $row[UserSourceUrlField::name()] = (new UserSourceUrlField($event->getUser()->sourceUrl()))->value();
        $row[UserClickNumberField::name()] = (new UserClickNumberField((string) $event->getUser()
                                                                                      ->getTotalClicks()))->value();
        $row[ZoneTypeField::name()] = (new ZoneTypeField($event->getZone()->getType()))->value();
        $row[ZoneCategoryIdField::name()] = (new ZoneCategoryIdField($event->getZone()->getCategoryId() ?? 0))->value();
        $row[ZoneLanguageField::name()] = (new ZoneLanguageField($event->getZone()->getLanguage()))->value();
        $row[ZoneDomainField::name()] = (new ZoneDomainField($event->getZone()->getDomain()))->value();
        $row[ZoneSearchKeywordField::name()] = (new ZoneSearchKeywordField($event->getZone()
                                                                                 ->getSearchKeyword()))->value();
        $row[ZoneSearchKeywordTranslationField::name()] =(new ZoneSearchKeywordField($event->getZone()
                                                                                           ->getSearchKeywordTranslation()))->value();
        $row[ZoneEmbedIdField::name()] = (new ZoneEmbedIdField($event->getZone()->getEmbedId()))->value();
        $row[ZoneGroupField::name()] = (new ZoneGroupField($event->getZone()->getGroup()))->value();

        $row[ItemThumbIdField::name()] = (new ItemThumbIdField($event->getItem()->getId()))->value();
        $row[ItemGalleryIdField::name()] = (new ItemGalleryIdField($event->getItem()->getGalleryId()))->value();

        return $row;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::NAME;
    }

    /**
     * @return string
     */
    public static function getBufferName(): string
    {
        return self::BUFFER_NAME;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    protected static function getSqlFields(): string
    {
        $collection = [];
        foreach (self::FIELDS as $fieldClass) {
            $collection[] = $fieldClass::toSql();
        }
        return implode(', ', $collection);
    }
}
