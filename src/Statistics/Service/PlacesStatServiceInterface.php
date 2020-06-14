<?php

namespace Noobus\GrootLib\Statistics\Service;

use Noobus\GrootLib\Statistics\Request\PlacesStatRequest;
use Noobus\GrootLib\Statistics\Response\PlacesStatResponse;

interface PlacesStatServiceInterface
{
    public function getStats(PlacesStatRequest $request): PlacesStatResponse;
}
