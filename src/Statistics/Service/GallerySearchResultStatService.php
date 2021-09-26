<?php

namespace Noobus\GrootLib\Statistics\Service;

use ClickHouseDB\Client;
use Noobus\GrootLib\Statistics\Clickhouse\Thumbs\GalleryStat;
use Noobus\GrootLib\Statistics\Request\GallerySearchResultStatRequest;
use Noobus\GrootLib\Statistics\Response\ThumbnailsStatResponse;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemGalleryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemThumbIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneSearchKeywordField;

class GallerySearchResultStatService
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var string
     */
    private string $table;

    /**
     * SearchResultStatService constructor.
     *
     * @param ClientFactory $clientFactory
     * @param string $table
     */
    public function __construct(
        ClientFactory $clientFactory,
        string        $table = 'stat_rotation_events_gallery_search_by_key'
    ) {
        $this->client = $clientFactory->getClient();
        $this->table = $table;
    }

    /**
     * @param GallerySearchResultStatRequest $request
     * @return ThumbnailsStatResponse
     */
    public function getStats(GallerySearchResultStatRequest $request): ThumbnailsStatResponse
    {
        $requestSql = $this->createSqlQuery();
        $requestData = $this->createSqlRequestData($request);

        $startTime = microtime(true);
        $result = $this->client->select($requestSql, $requestData);

        $endTime = microtime(true);

        $response = new ThumbnailsStatResponse();
        $response->setElapsedTime($endTime - $startTime);

        foreach ($result->rows() as $row) {
            $thumbnailStat = new GalleryStat($row['ItemThumbId'], $row['Clicks'], $row['Views'], $row['Ctr']);
            $response->pushItem($thumbnailStat);
        }

        return $response;
    }

    /**
     * @return string
     */
    protected function createSqlQuery(): string
    {
        $query = sprintf(
            'select  %s AS ItemGalleryId, 
                                sum(Clicks) as Clicks, 
                                sum(Views) as Views, 
                                if(Views>0, Clicks/Views, 0) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:domain
                            AND %s=:search_request
                            AND %s IN (:gallery_ids)
                            GROUP BY %s
                            HAVING Views>:min_views
                            ORDER BY Ctr DESC
                            LIMIT :limit OFFSET :offset',
            ItemThumbIdField::name(),
            $this->table,
            ZoneGroupField::name(),
            ZoneDomainField::name(),
            ZoneSearchKeywordField::name(),
            ItemGalleryIdField::name(),
            ItemGalleryIdField::name(),
        );

        return $query;
    }

    /**
     * @param GallerySearchResultStatRequest $request
     * @return array
     */
    protected function createSqlRequestData(GallerySearchResultStatRequest $request): array
    {
        return [
            'zone_group' => $request->getStatisticsGroup(),
            'domain' => $request->getDomain(),
            'search_request' => $request->getTranslatedSearchQuery(),
            'min_views' => $request->getMinViews(),
            'gallery_ids' => implode(', ', $request->getGalleryIds()),
            'limit' => $request->getLimit(),
            'offset' => $request->getOffset(),
        ];
    }
}
