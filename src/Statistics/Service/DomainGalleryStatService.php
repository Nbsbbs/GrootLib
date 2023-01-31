<?php

namespace Noobus\GrootLib\Statistics\Service;

use ClickHouseDB\Client;
use ClickHouseDB\Statement;
use Noobus\GrootLib\Statistics\Clickhouse\Galleries\GalleryStat;
use Noobus\GrootLib\Statistics\Request\DomainGalleriesRequest;
use Noobus\GrootLib\Statistics\Response\GalleriesStatResponse;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

class DomainGalleryStatService
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
     * @param DomainGalleriesRequest $request
     * @return GalleriesStatResponse
     */
    public function getStats(DomainGalleriesRequest $request): GalleriesStatResponse
    {

        $startTime = microtime(true);

        $statement = $this->mainQuery($request);
        $response = $this->processStatement($statement);

        $endTime = microtime(true);
        $response->setElapsedTime($endTime - $startTime);
        return $response;
    }

    /**
     * @param DomainGalleriesRequest $request
     * @return Statement
     */
    protected function mainQuery(DomainGalleriesRequest $request): Statement
    {
        $query = sprintf(
            'SELECT ItemGalleryId, SUM(Clicks) as Clicks, SUM(Views) as Views FROM %s WHERE ItemGalleryId IN (%s) AND ZoneGroup=:zone_group AND ZoneDomain=:zone_domain GROUP BY ItemGalleryId HAVING Views>=:min_views',
            $this->table,
            implode(',', $request->getIds())
        );

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
            $thumbnailStat = new GalleryStat($row['ItemGalleryId'], $row['Clicks'], $row['Views'], ($row['Views'] > 0) ? (number_format($row['Clicks'] / $row['Views'], 6)) : (0));
            $response->pushItem($thumbnailStat);
        }

        return $response;
    }

    /**
     * @param DomainGalleriesRequest $request
     * @return array
     */
    protected function createSqlRequestData(DomainGalleriesRequest $request): array
    {
        return [
            'zone_group' => $request->getStatisticsGroup(),
            'zone_domain' => $request->getDomain(),
            'min_views' => $request->getMinViews(),
        ];
    }
}
