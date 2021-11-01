<?php

namespace Noobus\GrootLib\Statistics\Service;

use ClickHouseDB\Client;
use ClickHouseDB\Statement;
use Noobus\GrootLib\Statistics\Clickhouse\Galleries\GalleryStat;
use Noobus\GrootLib\Statistics\Request\DomainGalleriesWithDefaultCtrRequest;
use Noobus\GrootLib\Statistics\Response\GalleriesStatResponse;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemGalleryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;

class DomainGalleryWithDefaultCtrService
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
        string        $table = 'test.stat_rotation_events_gallery_by_domain'
    ) {
        $this->client = $clientFactory->getClient();
        $this->client->useSession();
        $this->table = $table;
    }

    /**
     * @param DomainGalleriesWithDefaultCtrRequest $request
     * @return GalleriesStatResponse
     */
    public function getStats(DomainGalleriesWithDefaultCtrRequest $request): GalleriesStatResponse
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
     * @param DomainGalleriesWithDefaultCtrRequest $request
     */
    protected function createPrimaryTempTable(DomainGalleriesWithDefaultCtrRequest $request): void
    {
        $this->client->select('CREATE TEMPORARY TABLE dgwdcs_temp_items (GalleryId UInt32, DefaultCtr float)');

        $valuesToInsert = [];
        foreach ($request->getItems() as $item) {
            $valuesToInsert[] = ['GalleryId' => $item->getId(), 'DefaultCtr' => $item->getCtr()];
        }

        $this->client->insertAssocBulk('dgwdcs_temp_items', $valuesToInsert);
    }

    /**
     * @param DomainGalleriesWithDefaultCtrRequest $request
     * @return string
     */
    protected function createSecondaryTempTable(DomainGalleriesWithDefaultCtrRequest $request): void
    {
        $query = sprintf(
            'CREATE TEMPORARY TABLE dgwdcs_temp_stat AS select  %s AS ItemGalleryId, 
                                sum(Clicks) as Clicks, 
                                sum(Views) as Views, 
                                if(Views>0, Clicks/Views, null) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:domain
                            AND %s IN (%s)
                            GROUP BY %s
                            HAVING Views>:min_views',
            ItemGalleryIdField::name(),
            $this->table,
            ZoneGroupField::name(),
            ZoneDomainField::name(),
            ItemGalleryIdField::name(),
            implode(', ', $request->getIds()),
            ItemGalleryIdField::name(),
        );
        $requestData = $this->createSqlRequestData($request);

        $statement = $this->client->select($query, $requestData);
        $statement->error();
    }

    /**
     * @param DomainGalleriesWithDefaultCtrRequest $request
     * @return Statement
     */
    protected function mainQuery(DomainGalleriesWithDefaultCtrRequest $request): Statement
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
     * @param DomainGalleriesWithDefaultCtrRequest $request
     * @return array
     */
    protected function createSqlRequestData(DomainGalleriesWithDefaultCtrRequest $request): array
    {
        return [
            'zone_group' => $request->getStatisticsGroup(),
            'domain' => $request->getDomain(),
            'min_views' => $request->getMinViews(),
            'gallery_ids' => implode(', ', $request->getIds()),
            'limit' => $request->getLimit(),
            'offset' => $request->getOffset(),
        ];
    }
}
