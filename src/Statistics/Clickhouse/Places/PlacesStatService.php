<?php

namespace Noobus\GrootLib\Statistics\Clickhouse\Places;

use ClickHouseDB\Client;
use Noobus\GrootLib\Statistics\Request\PlacesStatRequest;
use Noobus\GrootLib\Statistics\Response\PlacesStatResponse;
use Noobus\GrootLib\Statistics\Service\PlacesStatServiceInterface;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\DateTimeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\EventTypeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\EventZonePlaceIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\UserSessionIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneDomainField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Table\ThumbEventTable;

class PlacesStatService implements PlacesStatServiceInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->client = $clientFactory->getClient();
    }

    public function getStats(PlacesStatRequest $request): PlacesStatResponse
    {
        $requstSql = $this->createSqlQuery();
        $requestData = $this->createSqlRequstData($request);

        $startTime = microtime(true);
        $result = $this->client->select($requstSql, $requestData);
        $endTime = microtime(true);

        $response = new PlacesStatResponse();
        $response->setElapsedTime($endTime - $startTime);

        foreach ($result->rows() as $row) {
            $placementStat = new PlacementStat($row['PlaceId'], $row['Clicks'], $row['Views'], $row['Ctr']);
            $response->pushPlace($placementStat);
        }

        return $response;
    }

    protected function createSqlQuery(): string
    {
        $query = sprintf(
                'select  %s AS PlaceId, 
                                sumIf(1, %s=\'click\') AS Clicks, 
                                sumIf(1, %s=\'view\') AS Views, 
                                if(Views>0, Clicks/Views, 0) AS Ctr 
                        FROM %s 
                        WHERE 
                                %s=:domain 
                            AND %s=:zone_group 
                            AND %s>today()-:days_interval 
                            AND %s IN 
                                (
                                select %s 
                                from %s 
                                WHERE %s=\'click\' 
                                GROUP BY %s 
                                HAVING 
                                       count()>=:min_clicks 
                                   AND count()<=:max_clicks
                                ) 
                            GROUP BY PlaceId 
                            ORDER BY PlaceId',
            EventZonePlaceIdField::name(),
            EventTypeField::name(),
            EventTypeField::name(),
            ThumbEventTable::getName(),
            ZoneDomainField::name(),
            ZoneGroupField::name(),
            DateTimeField::name(),
            UserSessionIdField::name(),
            UserSessionIdField::name(),
            ThumbEventTable::getName(),
            EventTypeField::name(),
            UserSessionIdField::name(),
        );

        return $query;
    }

    /**
     * @param PlacesStatRequest $request
     * @return array
     */
    protected function createSqlRequstData(PlacesStatRequest $request): array
    {
        return [
            'domain' => $request->getDomain(),
            'zone_group' => $request->getStatisticsGroup(),
            'days_interval' => $request->getTimespan(),
            'min_clicks' => $request->getMinClicks(),
            'max_clicks' => $request->getMaxClicks(),
        ];
    }
}
