<?php

namespace Noobus\GrootLib\Statistics\Service\Gallery;

use ClickHouseDB\Client;
use ClickHouseDB\Statement;
use Noobus\GrootLib\Statistics\Clickhouse\Galleries\GalleryStat;
use Noobus\GrootLib\Statistics\Request\Gallery\FixedQueryStatRequest;
use Noobus\GrootLib\Statistics\Response\GalleriesStatResponse;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemGalleryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneFixedQueryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;

class FixedQueryStatService
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
        string        $table = 'test.stat_rotation_events_gallery_by_query_id'
    ) {
        $this->client = $clientFactory->getClient();
        $this->client->useSession();
        $this->table = $table;
    }

    /**
     * @param FixedQueryStatRequest $request
     * @return GalleriesStatResponse
     */
    public function getStats(FixedQueryStatRequest $request): GalleriesStatResponse
    {
        $startTime = microtime(true);
        $this->createPrimaryTempTable($request);
        $this->createSecondaryTempTable($request);

        $statement = $this->mainQuery($request);
        $response = $this->processStatement($statement);

        $endTime = microtime(true);
        $response->setElapsedTime($endTime - $startTime);
        return $response;
    }

    /**
     * @param FixedQueryStatRequest $request
     */
    protected function createPrimaryTempTable(FixedQueryStatRequest $request): void
    {
        $this->client->select('CREATE TEMPORARY TABLE dgwdcs_temp_items (GalleryId UInt32, DefaultCtr float)');

        $valuesToInsert = [];
        foreach ($request->getItems() as $item) {
            $valuesToInsert[] = ['GalleryId' => $item->getId(), 'DefaultCtr' => $item->getCtr()];
        }

        $this->client->insertAssocBulk('dgwdcs_temp_items', $valuesToInsert);
    }

    /**
     * @param FixedQueryStatRequest $request
     * @return string
     */
    protected function createSecondaryTempTable(FixedQueryStatRequest $request): void
    {
        $query = sprintf(
            'CREATE TEMPORARY TABLE dgwdcs_temp_stat AS select  %s AS ItemGalleryId, 
                                sum(Clicks) as Clicks, 
                                sum(Views) as Views, 
                                multiIf(Views>:min_views, Clicks/Views, Clicks>0, Clicks/(Views+1), null) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:domain
                            AND %s=:query_id
                            AND %s IN (select GalleryId from dgwdcs_temp_items)
                            GROUP BY %s
                            HAVING Views>:min_views OR Clicks>0',
            ItemGalleryIdField::name(),
            $this->table,
            ZoneGroupField::name(),
            ZoneDomainField::name(),
            ZoneFixedQueryIdField::name(),
            ItemGalleryIdField::name(),
            ItemGalleryIdField::name(),
        );
        $requestData = $this->createSqlRequestData($request);

        $statement = $this->client->select($query, $requestData);
        $statement->error();
    }

    /**
     * @param FixedQueryStatRequest $request
     * @return Statement
     */
    protected function mainQuery(FixedQueryStatRequest $request): Statement
    {
        $query = sprintf('
        select 
            t1.GalleryId as GalleryId, 
            if(t2.Views IS NOT NULL, t2.Views, 0) as Views, 
            if(t2.Clicks IS NOT NULL, t2.Clicks, 0) as Clicks, 
            if (t2.Ctr IS NOT NULL, t2.Ctr, t1.DefaultCtr) as Ctr FROM dgwdcs_temp_items t1 LEFT OUTER JOIN dgwdcs_temp_stat t2 ON t1.GalleryId=t2.ItemGalleryId order by Ctr desc limit :limit offset :offset');

        return $this->client->select($query, $this->createSqlRequestData($request));
    }

    /**
     * @param Statement $statement
     * @return GalleriesStatResponse
     */
    protected function processStatement(Statement $statement): GalleriesStatResponse
    {
        $response = new GalleriesStatResponse();

        foreach ($statement->rows() as $row) {
            $thumbnailStat = new GalleryStat($row['GalleryId'], $row['Clicks'], $row['Views'], $row['Ctr']);
            $response->pushItem($thumbnailStat);
        }

        return $response;
    }

    /**
     * @param FixedQueryStatRequest $request
     * @return array
     */
    protected function createSqlRequestData(FixedQueryStatRequest $request): array
    {
        return [
            'zone_group' => $request->getStatisticsGroup(),
            'domain' => $request->getDomain(),
            'min_views' => $request->getMinViews(),
            'query_id' => $request->getQueryId(),
            'limit' => $request->getLimit(),
            'offset' => $request->getOffset(),
        ];
    }
}
