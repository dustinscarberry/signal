<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Api\WidgetDataGenerator;
use App\Service\Factory\WidgetFactory;

class WidgetDataApiController extends ApiController
{
  #[Route('/api/v1/widgetsdata/{hashId}', name: 'readWidgetsData', methods: ['GET'])]
  public function readWidgetsData($hashId, WidgetDataGenerator $widgetDataGenerator, WidgetFactory $widgetFactory)
  {
    $widget = $widgetFactory->getWidget($hashId);

    if (!$widget) return $this->respondWithErrors(['Invalid data']);

    $rsp = $widgetDataGenerator->getData($widget);
    return $this->respond($rsp);
  }
}
