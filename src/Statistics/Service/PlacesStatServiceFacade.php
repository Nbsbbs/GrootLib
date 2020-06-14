<?php

namespace Noobus\GrootLib\Statistics\Service;

use Noobus\GrootLib\Statistics\Request\PlacesStatRequest;
use Noobus\GrootLib\Statistics\Response\PlacesStatResponse;

class PlacesStatServiceFacade implements PlacesStatServiceInterface
{
    /**
     * @var PlacesStatServiceInterface
     */
    private $service;

    public function __construct(PlacesStatServiceInterface $concreteService)
    {
        $this->service = $concreteService;
    }

    public function getStats(PlacesStatRequest $request): PlacesStatResponse
    {
        return $this->service->getStats($request);
    }
}
