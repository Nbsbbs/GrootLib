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

class DomainSearchStatService
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
     * @param ClientFactory $clientFactory
     * @param string $database
     * @param string $table
     */
    public function __construct(
        ClientFactory $clientFactory,
        string        $database = 'test',
        string        $table = 'stat_rotation_events_search_by_key'
    ) {
        $this->client = $clientFactory->getClient();
        $this->database = $database;
        $this->table = $table;
    }

    /**
     * @param DomainSearchStatRequest $request
     * @return GalleriesWithThumbnailsStatResponse
     * @throws Exception
     */
    public function getStats(DomainSearchStatRequest $request): GalleriesWithThumbnailsStatResponse
    {
        if (!$request->getDomain()) {
            $statement = $this->sqlRequestWithoutDomain($request);
        } else {
            $statement = $this->sqlRequestWithDomain($request);
        }

        return $this->processStatement($statement);
    }

    /**
     * @param DomainSearchStatRequest $request
     * @return Statement
     */
    protected function sqlRequestWithDomain(DomainSearchStatRequest $request): Statement
    {
        $query = sprintf(
            'select  %s as ItemGalleryId,
                            %s as ItemThumbId, 
                            sum(Clicks) as Clicks, 
                            sum(Views) as Views, 
                            if(Views>0, Clicks/Views, 0) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:domain
                            AND %s=:search_request
                            GROUP BY %s, %s
                            HAVING Views>:min_views
                            ORDER BY Ctr DESC
                            LIMIT :limit OFFSET :offset',
            ItemGalleryIdField::name(),
            ItemThumbIdField::name(),
            $this->database . '.' . $this->table,
            ZoneGroupField::name(),
            ZoneDomainField::name(),
            ZoneSearchKeywordTranslationField::name(),
            ItemGalleryIdField::name(),
            ItemThumbIdField::name(),
        );

        $data = $this->createSqlRequestData($request);
        return $this->client->select($query, $data);
    }

    /**
     * @param DomainSearchStatRequest $request
     * @return Statement
     */
    protected function sqlRequestWithoutDomain(DomainSearchStatRequest $request): Statement
    {
        $query = sprintf(
            'select  %s as ItemGalleryId,
                            %s as ItemThumbId, 
                            sum(Clicks) as Clicks, 
                            sum(Views) as Views, 
                            if(Views>0, Clicks/Views, 0) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s=:search_request
                            GROUP BY %s, %s
                            HAVING Views>:min_views
                            ORDER BY Ctr DESC
                            LIMIT :limit OFFSET :offset',
            ItemGalleryIdField::name(),
            ItemThumbIdField::name(),
            $this->database . '.' . $this->table,
            ZoneGroupField::name(),
            ZoneSearchKeywordTranslationField::name(),
            ItemGalleryIdField::name(),
            ItemThumbIdField::name(),
        );

        $data = $this->createSqlRequestData($request);
        return $this->client->select($query, $data);
    }

    /**
     * @param Statement $statement
     * @return GalleriesWithThumbnailsStatResponse
     */
    protected function processStatement(Statement $statement): GalleriesWithThumbnailsStatResponse
    {
        $response = new GalleriesWithThumbnailsStatResponse();
        $response->setTotalRows($statement->countAll());
        $response->setElapsedTime($statement->statistics('elapsed'));
        foreach ($statement->rows() as $row) {
            $thumbnailStat = new GalleryWithThumbnailStat($row['ItemGalleryId'], $row['ItemThumbId'], $row['Clicks'], $row['Views'], $row['Ctr']);
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
