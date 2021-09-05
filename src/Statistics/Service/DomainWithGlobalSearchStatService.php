<?php

namespace Noobus\GrootLib\Statistics\Service;

use ClickHouseDB\Client;
use ClickHouseDB\Statement;
use Exception;
use Noobus\GrootLib\Statistics\Clickhouse\GalleryWithThumbnailStat;
use Noobus\GrootLib\Statistics\Request\DomainSearchStatRequest;
use Noobus\GrootLib\Statistics\Response\GalleriesWithThumbnailsStatResponse;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemGalleryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemThumbIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneSearchKeywordTranslationField;

class DomainWithGlobalSearchStatService
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var string
     */
    private string $database;

    /**
     * @var string
     */
    private string $table;

    /**
     * @var string
     */
    private string $globalCtrTable;

    /**
     * @param ClientFactory $clientFactory
     * @param string $database
     * @param string $table
     * @param string $globalCtrTable
     */
    public function __construct(
        ClientFactory $clientFactory,
        string        $database = 'test',
        string        $table = 'stat_rotation_events_search_by_key',
        string        $globalCtrTable = 'stat_rotation_events_global_ctr2'
    ) {
        $this->client = $clientFactory->getClient();
        $this->client->useSession();
        $this->database = $database;
        $this->table = $table;
        $this->globalCtrTable = $globalCtrTable;
    }

    /**
     * @param DomainSearchStatRequest $request
     * @return GalleriesWithThumbnailsStatResponse
     * @throws Exception
     */
    public function getStats(DomainSearchStatRequest $request): GalleriesWithThumbnailsStatResponse
    {
        $startTime = microtime(true);
        if (!$request->getDomain()) {
            throw new \InvalidArgumentException('Service ' . __CLASS__ . ' requires domain attribute on request');
        } else {
            $statement = $this->sqlRequestWithDomain($request);
        }

        $processedStatement = $this->processStatement($statement);
        $processedStatement->setElapsedTime(microtime(true) - $startTime);
        return $processedStatement;
    }

    protected function createSearchStatCtrTable(DomainSearchStatRequest $searchStatRequest): string
    {
        $table = 'ctr1_' . mt_rand(0, 1000000);

        $query = sprintf('CREATE TEMPORARY TABLE ' . $table . ' AS 
                        SELECT  %s as ItemGalleryId,
                            %s as ItemThumbId, 
                            sum(Clicks) as Clicks, 
                            sum(Views) as Views
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:domain
                            AND %s=:search_request
                            GROUP BY %s, %s
        ', ItemGalleryIdField::name(),
            ItemThumbIdField::name(),
            $this->database . '.' . $this->table,
            ZoneGroupField::name(),
            ZoneDomainField::name(),
            ZoneSearchKeywordTranslationField::name(),
            ItemGalleryIdField::name(),
            ItemThumbIdField::name());

        $result = $this->client->select($query, $this->createSqlRequestData($searchStatRequest));

        return $table;
    }

    protected function createGlobalStatCtrTable(string $firstTableName): string
    {
        $table = 'ctr2_' . mt_rand(0, 1000000);

        $query = sprintf('CREATE TEMPORARY TABLE %s AS 
                        SELECT  %s as ItemGalleryId,
                            %s as ItemThumbId, 
                            sum(Clicks) as Clicks, 
                            sum(Views) as Views
                        FROM %s 
                        WHERE ZoneGroup=%s AND (ItemGalleryId , ItemThumbId) in (select ItemGalleryId, ItemThumbId from %s) 
                            GROUP BY %s, %s
        ', $table,
            ItemGalleryIdField::name(),
            ItemThumbIdField::name(),
            $this->database . '.' . $this->globalCtrTable,
            ZoneGroupField::name(),
            $firstTableName,
            ItemGalleryIdField::name(),
            ItemThumbIdField::name());

        $result = $this->client->select($query, []);

        return $table;
    }

    /**
     * @param DomainSearchStatRequest $request
     * @return Statement
     */
    protected function sqlRequestWithDomain(DomainSearchStatRequest $request): Statement
    {
        $table1 = $this->createSearchStatCtrTable($request);
        $table2 = $this->createGlobalStatCtrTable($table1);

        $query = sprintf(
            'SELECT t1.ItemGalleryId, t1.ItemThumbId, t1.Clicks as cs, t1.Views as vs, t2.Clicks as c0, t2.Views as v0, if(v0>200, 200, v0) as vn0, if(v0>200, toUInt64(200*c0/v0), c0) as cn0, (vs+vn0) as vt, (cs+cn0) as ct, if(vt>0, ct/vt, 0) as ctr FROM %s t1 LEFT OUTER JOIN %s t2 ON (t1.ItemGalleryId=t2.ItemGalleryId AND t1.ItemThumbId=t2.ItemThumbId) order by ctr desc limit :limit offset :offset',
            $table1,
            $table2
        );

        return $this->client->select($query, $this->createSqlRequestData($request));
    }

    /**
     * @param Statement $statement
     * @return GalleriesWithThumbnailsStatResponse
     */
    protected function processStatement(Statement $statement): GalleriesWithThumbnailsStatResponse
    {
        $response = new GalleriesWithThumbnailsStatResponse();
        $response->setTotalRows($statement->countAll());

        foreach ($statement->rows() as $row) {
            $thumbnailStat = new GalleryWithThumbnailStat($row['ItemGalleryId'], $row['ItemThumbId'], $row['ct'], $row['vt'], $row['ctr']);
            $response->pushItem($thumbnailStat);
        }

        return $response;
    }

    /**
     * @param DomainSearchStatRequest $request
     * @return array
     */
    protected function createSqlRequestData(DomainSearchStatRequest $request): array
    {
        return [
            'zone_group' => $request->getZoneGroup(),
            'domain' => $request->getDomain(),
            'search_request' => $request->getTranslatedQuery(),
            'min_views' => $request->getMinViews(),
            'limit' => $request->getLimit(),
            'offset' => $request->getOffset(),
        ];
    }
}
