<?php

namespace Noobus\GrootLib\Statistics\Service;

use ClickHouseDB\Client;
use Noobus\GrootLib\Statistics\Clickhouse\Thumbs\ThumbnailStat;
use Noobus\GrootLib\Statistics\Request\SearchResultStatRequest;
use Noobus\GrootLib\Statistics\Response\ThumbnailsStatResponse;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemThumbIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneSearchKeywordField;

/**
 * Class SearchResultStatService
 *
 * Используется для получения статистики по конкретному поисковому запросу
 *
 * @package Noobus\GrootLib\Statistics\Clickhouse\Places
 */
class SearchResultStatService
{
    const DATE_INTERVAL = 90;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * SearchResultStatService constructor.
     *
     * @param ClientFactory $clientFactory
     */
    public function __construct(ClientFactory $clientFactory)
    {
        $this->client = $clientFactory->getClient();
    }

    /**
     * @param SearchResultStatRequest $request
     * @return ThumbnailsStatResponse
     */
    public function getStats(SearchResultStatRequest $request): ThumbnailsStatResponse
    {
        if ($request->isIgnoreDomain()) {
            $requestSql = $this->createSqlQueryWithoutDomain();
        } else {
            $requestSql = $this->createSqlQuery();
        }
        $requestData = $this->createSqlRequestData($request);

        $startTime = microtime(true);
        $result = $this->client->select($requestSql, $requestData);

        $endTime = microtime(true);

        $response = new ThumbnailsStatResponse();
        $response->setElapsedTime($endTime - $startTime);

        foreach ($result->rows() as $row) {
            $thumbnailStat = new ThumbnailStat($row['ItemThumbId'], $row['Clicks'], $row['Views'], $row['Ctr']);
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
            'select  %s AS ItemThumbId, 
                                sum(Clicks) as Clicks, 
                                sum(Views) as Views, 
                                if(Views>0, Clicks/Views, 0) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:domain
                            AND %s=:search_request
                            AND %s>today()-:days_interval
                            GROUP BY %s
                            HAVING Views>:min_views
                            ORDER BY Ctr DESC
                            LIMIT :limit OFFSET :offset',
            ItemThumbIdField::name(),
            'test.stat_rotation_events_search_query',
            ZoneGroupField::name(),
            ZoneDomainField::name(),
            ZoneSearchKeywordField::name(),
            'EventDate',
            ItemThumbIdField::name(),
        );

        return $query;
    }

    /**
     * @return string
     */
    protected function createSqlQueryWithoutDomain(): string
    {
        $query = sprintf(
            'select  %s AS ItemThumbId, 
                                sum(Clicks) as Clicks, 
                                sum(Views) as Views, 
                                if(Views>0, Clicks/Views, 0) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:search_request
                            AND %s>today()-:days_interval
                            GROUP BY %s
                            HAVING Views>:min_views
                            ORDER BY Ctr DESC
                            LIMIT :limit OFFSET :offset',
            ItemThumbIdField::name(),
            'test.stat_rotation_events_search_query',
            ZoneGroupField::name(),
            ZoneSearchKeywordField::name(),
            'EventDate',
            ItemThumbIdField::name(),
        );

        return $query;
    }

    /**
     * @param SearchResultStatRequest $request
     * @return array
     */
    protected function createSqlRequestData(SearchResultStatRequest $request): array
    {
        return [
            'zone_group' => $request->getStatisticsGroup(),
            'domain' => $request->getDomain(),
            'search_request' => $request->getSearchRequest(),
            'days_interval' => self::DATE_INTERVAL,
            'min_views' => $request->getMinViews(),
            'limit' => $request->getLimit(),
            'offset' => $request->getOffset(),
        ];
    }
}
