<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\WidgetAPIType;
use App\Entity\Widget;
use App\Service\Manager\WidgetManager;

class WidgetApiController extends ApiController
{
  /**
   * @Route("/api/v1/widgets", name="readWidgets", methods={"GET"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function readWidgets(WidgetManager $widgetManager)
  {
    $widgets = $widgetManager->getWidgets();
    return $this->respond($widgets);
  }

  /**
   * @Route("/api/v1/widgets/{hashId}", name="readWidget", methods={"GET"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function readWidget($hashId, WidgetManager $widgetManager)
  {
    $widget = $widgetManager->getWidget($hashId);
    return $this->respond($widget);
  }

  /**
   * @Route("/api/v1/widgets", name="createWidget", methods={"POST"})
   * @Security("is_granted('ROLE_ADMIN')")
   * Pass {type, sortorder, attributes}
   */
  public function createWidget(Request $req, WidgetManager $widgetManager)
  {
    $widget = new Widget();
    $form = $this->createForm(WidgetAPIType::class, $widget);
    $data = json_decode($req->getContent(), true);

    if (isset($data['attributes']))
      $data['attributes'] = json_encode($data['attributes']);

    $form->submit($data);

    //save new widget to database if valid
    if ($form->isSubmitted() && $form->isValid())
    {
      $widgetManager->createWidget($widget);
      return $this->respond($widget);
    }

    return $this->respondWithErrors([
      'Invalid Data'
    ]);
  }

  /**
   * @Route("/api/v1/widgets/{hashId}", name="updateWidget", methods={"PATCH"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function updateWidget($hashId, Request $req, WidgetManager $widgetManager)
  {
    $widget = $widgetManager->getWidget($hashId);

    if (!$widget)
      throw $this->createNotFoundException(
        'No widget found for id '. $hashId
      );

    $form = $this->createForm(WidgetAPIType::class, $widget);
    $data = json_decode($req->getContent(), true);

    if (isset($data['attributes']))
      $data['attributes'] = json_encode($data['attributes']);

    $form->submit($data);

    //save widget updates to database if valid
    if ($form->isSubmitted() && $form->isValid())
    {
      $widgetManager->updateWidget();
      
      return $this->respond($widget);
    }

    return $this->respondWithErrors([
      'Invalid Data'
    ]);
  }

  /**
   * @Route("/api/v1/widgets/{hashId}", name="deleteWidget", methods={"DELETE"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function deleteWidget($hashId, WidgetManager $widgetManager)
  {
    $widget = $widgetManager->getWidget($hashId);

    if (!$widget)
      throw $this->createNotFoundException(
        'No widget found for id '. $hashId
      );

    //delete widget
    $widgetManager->deleteWidget($widget);

    return $this->respondWithNull();
  }
}
