<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\WidgetAPIType;
use App\Entity\Widget;
use App\Service\Factory\WidgetFactory;

class WidgetApiController extends ApiController
{
  #[Route('/api/v1/widgets', name: 'readWidgets', methods: ['GET'])]
  #[IsGranted('ROLE_ADMIN')]
  public function readWidgets(WidgetFactory $widgetFactory)
  {
    $widgets = $widgetFactory->getWidgets();
    return $this->respond($widgets);
  }

  #[Route('/api/v1/widgets/{hashId}', name: 'readWidget', methods: ['GET'])]
  #[IsGranted('ROLE_ADMIN')]
  public function readWidget($hashId, WidgetFactory $widgetFactory)
  {
    $widget = $widgetFactory->getWidget($hashId);
    return $this->respond($widget);
  }

  /**
   * Pass {type, sortorder, attributes}
   */
  #[Route('/api/v1/widgets', name: 'createWidget', methods: ['POST'])]
  #[IsGranted('ROLE_ADMIN')]
  public function createWidget(Request $req, WidgetFactory $widgetFactory)
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
      $widgetFactory->createWidget($widget);
      return $this->respond($widget);
    }

    return $this->respondWithErrors([
      'Invalid Data'
    ]);
  }

  #[Route('/api/v1/widgets/{hashId}', name: 'updateWidget', methods: ['PATCH'])]
  #[IsGranted('ROLE_ADMIN')]
  public function updateWidget($hashId, Request $req, WidgetFactory $widgetFactory)
  {
    $widget = $widgetFactory->getWidget($hashId);

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
    if ($form->isSubmitted() && $form->isValid()) {
      $widgetFactory->updateWidget();
      
      return $this->respond($widget);
    }

    return $this->respondWithErrors([
      'Invalid Data'
    ]);
  }

  #[Route('/api/v1/widgets/{hashId}', name: 'deleteWidget', methods: ['DELETE'])]
  #[IsGranted('ROLE_ADMIN')]
  public function deleteWidget($hashId, WidgetFactory $widgetFactory)
  {
    $widget = $widgetFactory->getWidget($hashId);

    if (!$widget)
      throw $this->createNotFoundException(
        'No widget found for id '. $hashId
      );

    //delete widget
    $widgetFactory->deleteWidget($widget);

    return $this->respondWithNull();
  }
}
