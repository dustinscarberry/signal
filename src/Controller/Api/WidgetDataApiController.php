<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Service\Api\WidgetDataGenerator;
use App\Service\Manager\WidgetManager;

class WidgetDataApiController extends ApiController
{
  /**
   * @Route("/api/v1/widgetsdata/{hashId}", name="readWidgetsData", methods={"GET"})
   */
  public function readWidgetsData($hashId, WidgetDataGenerator $widgetDataGenerator, WidgetManager $widgetManager)
  {
    $widget = $widgetManager->getWidget($hashId);

    if ($widget)
    {
      $rsp = $widgetDataGenerator->getData($widget);
      return $this->respond($rsp);
    }

    return $this->respondWithErrors(['Invalid data']);
  }
}
