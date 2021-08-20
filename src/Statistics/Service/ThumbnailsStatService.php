<?php

namespace Noobus\GrootLib\Service;

use ClickHouseDB\Client;
use Nbsbbs\Common\ThumbnailIdentifier;
use Noobus\GrootLib\Statistics\Clickhouse\Thumbs\ThumbnailStat;
use Noobus\GrootLib\Statistics\Request\ThumbnailsStatRequest;
use Noobus\GrootLib\Statistics\Response\ThumbnailsStatResponse;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\DateTimeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\EventTypeField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemGalleryIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ItemThumbIdField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\ZoneGroupField;
use Noobus\GrootLib\Storage\Clickhouse\Entity\Table\RotationEventTable;

class ThumbnailsStatService
{
    private Client $client;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->client = $clientFactory->getClient();
    }

    public function getStats(ThumbnailsStatRequest $request): ThumbnailsStatResponse
    {
        $requstSql = $this->createSqlQuery($request);
        $requestData = $this->createSqlRequestData($request);

        $startTime = microtime(true);
        $result = $this->client->select($requstSql, $requestData);
        $endTime = microtime(true);

        $response = new ThumbnailsStatResponse();
        $response->setElapsedTime($endTime - $startTime);

        foreach ($result->rows() as $row) {
            $placementStat = new ThumbnailStat($row['ItemThumbId'], $row['Clicks'], $row['Views'], $row['Ctr']);
            $response->pushItem($placementStat);
        }

        return $response;
    }

    protected function createSqlQuery(ThumbnailsStatRequest $request): string
    {
        $query = sprintf(
            'select  %s as ItemGalleryId, 
                            %s AS ItemThumbId, 
                            sumIf(1, %s=\'click\') AS Clicks, 
                            sumIf(1, %s=\'view\') AS Views, 
                            if(Views>0, Clicks/Views, 0) AS Ctr 
                        FROM %s 
                        WHERE %s=:zone_group 
                            AND %s>today()-:days_interval 
                            AND (ItemGalleryId, ItemThumbId) IN (%s)
                            GROUP BY ItemGalleryId, ItemThumbId
                            ORDER BY Ctr DESC',
            ItemGalleryIdField::name(),
            ItemThumbIdField::name(),
            EventTypeField::name(),
            EventTypeField::name(),
            RotationEventTable::getName(),
            ZoneGroupField::name(),
            DateTimeField::name(),
            $this->createInStatementPart($request->getThumbnails()),
        );

        return $query;
    }

    /**
     * @param ThumbnailsStatRequest $request
     * @return array
     */
    protected function createSqlRequestData(ThumbnailsStatRequest $request): array
    {
        return [
            'zone_group' => $request->getStatGroup(),
            'days_interval' => 365,
        ];
    }

    /**
     * @param array $thumbnails
     * @return string
     */
    protected function createInStatementPart(array $thumbnails): string
    {
        $result = [];
        foreach ($thumbnails as $thumbnail) {
            if (!($thumbnail instanceof ThumbnailIdentifier)) {
                throw new \InvalidArgumentException('array \$thumbnails must be an array of ThumbnailIdentifier');
            }
            $result[] = sprintf('(%d, %d)', $thumbnail->getGalleryId(), $thumbnail->getThumbnailId());
        }
        return implode(', ', $result);
    }
}
