<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Widget;
use App\Service\Api\WidgetDataGenerator;

class WidgetDataApiController extends ApiController
{
  /**
   * @Route("/api/v1/widgetsdata/{id}", name="readWidgetsData", requirements={"id"="\d+"}, methods={"GET"})
   */
  public function readWidgetsData($id, WidgetDataGenerator $widgetDataGenerator)
  {
    $widget = $this->getDoctrine()
      ->getRepository(Widget::class)
      ->find($id);

    if ($widget)
    {
      $rsp = $widgetDataGenerator->getData($widget);
      return $this->respond($rsp);
    }

    return $this->respondWithErrors(['Invalid data']);
  }
}
