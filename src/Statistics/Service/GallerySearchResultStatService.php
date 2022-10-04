<?php

namespace Noobus\GrootLib\Statistics\Service;

use ClickHouseDB\Client;
use ClickHouseDB\Statement;
use Noobus\GrootLib\Statistics\Clickhouse\Galleries\GalleryStat;
use Noobus\GrootLib\Statistics\Request\GallerySearchResultStatRequest;
use Noobus\GrootLib\Statistics\Response\GalleriesStatResponse;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemGalleryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneSearchKeywordTranslationField;

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
        string        $table = 'test.stat_rotation_events_gallery_search_by_key'
    ) {
        $this->client = $clientFactory->getClient();
        $this->client->useSession();
        $this->table = $table;
    }

    /**
     * @param GallerySearchResultStatRequest $request
     * @return GalleriesStatResponse
     */
    public function getStats(GallerySearchResultStatRequest $request): GalleriesStatResponse
    {
        if (sizeof($request->getGalleryIds()) === 0) {
            return new GalleriesStatResponse();
        }
        $startTime = microtime(true);
        try {
            $this->createPrimaryTempTable($request);
        } catch (\Throwable $e) {
            try {
                $this->createPrimaryTempTable($request);
            } catch (\Exception $e) {
                throw new \Exception('Error creating first table (2nd attempt): ' . $e->getMessage());
            }
        }

        try {
            $this->createSecondaryTempTable($request);
        } catch (\Throwable $e) {
            try {
                $this->createSecondaryTempTable($request);
            } catch (\Throwable $e) {
                throw new \Exception('Error creating second table (2nd attempt): ' . $e->getMessage().' session '.$this->client->getSession());
            }
        }

        try {
            $statement = $this->mainQuery($request);
        } catch (\Throwable $e) {
            throw new \Exception('Error making main query: ' . $e->getMessage());
        }

        $response = $this->processStatement($statement);

        $endTime = microtime(true);
        $response->setElapsedTime($endTime - $startTime);
        return $response;
    }

    /**
     * @param GallerySearchResultStatRequest $request
     */
    protected function createPrimaryTempTable(GallerySearchResultStatRequest $request): void
    {
        $query = sprintf(
            'CREATE TEMPORARY TABLE gsrss_temp_items AS SELECT arrayJoin([%s]) as GalleryId, %.6f*(1+rand(GalleryId)/4294967295) as DefaultCtr',
            implode(', ', $request->getGalleryIds()),
            $request->getDefaultCtr()
        );

        $result = $this->client->select($query);
        $result->error();
    }

    /**
     * @param GallerySearchResultStatRequest $request
     * @return void
     */
    protected function createSecondaryTempTable(GallerySearchResultStatRequest $request): void
    {
        $query = sprintf(
            'CREATE TEMPORARY TABLE gsrss_temp_stat AS select  %s AS ItemGalleryId, 
                                sum(Clicks) as Clicks, 
                                sum(Views) as Views, 
                                multiIf(Views>0, Clicks/Views, Clicks>0, Clicks/(Views+1), null) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:domain
                            AND %s=:search_request
                            AND %s IN (%s)
                            GROUP BY %s
                            HAVING Views>:min_views OR Clicks>0',
            ItemGalleryIdField::name(),
            $this->table,
            ZoneGroupField::name(),
            ZoneDomainField::name(),
            ZoneSearchKeywordTranslationField::name(),
            ItemGalleryIdField::name(),
            implode(', ', $request->getGalleryIds()),
            ItemGalleryIdField::name(),
        );

        $requestData = $this->createSqlRequestData($request);
        $statement = $this->client->select($query, $requestData);
        $statement->error();
    }

    /**
     * @param GallerySearchResultStatRequest $request
     * @return Statement
     */
    protected function mainQuery(GallerySearchResultStatRequest $request): Statement
    {
        $query = sprintf('
        select 
            t1.GalleryId as GalleryId, 
            if(t2.Views IS NOT NULL, t2.Views, 0) as Views, 
            if(t2.Clicks IS NOT NULL, t2.Clicks, 0) as Clicks, 
            if (t2.Ctr IS NOT NULL, t2.Ctr, t1.DefaultCtr) as Ctr FROM gsrss_temp_items t1 LEFT OUTER JOIN gsrss_temp_stat t2 ON t1.GalleryId=t2.ItemGalleryId order by Ctr desc limit :limit offset :offset');

        return $this->client->select($query, $this->createSqlRequestData($request));
    }

    /**
     * @param Statement $statement
     * @return GalleriesStatResponse
     */
    protected function processStatement(Statement $statement): GalleriesStatResponse
    {
        $response = new GalleriesStatResponse();

        try {
            $rows = $statement->rows();
        } catch (\Throwable $e) {
            // retry
            try {
                $rows = $statement->rows();
            } catch (\Throwable $e) {
                throw new \Exception('Error fetching rows (2nd try): ' . $e->getMessage());
            }
        }

        foreach ($rows as $row) {
            $thumbnailStat = new GalleryStat($row['GalleryId'], $row['Clicks'], $row['Views'], $row['Ctr']);
            $response->pushItem($thumbnailStat);
        }

        return $response;
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
