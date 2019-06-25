<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\WidgetAPIType;
use App\Entity\Widget;

class WidgetApiController extends ApiController
{
  /**
   * @Route("/api/v1/widgets", name="readWidgets", methods={"GET"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function readWidgets()
  {
    $widgets = $this->getDoctrine()
      ->getRepository(Widget::class)
      ->findAllSorted();

    return $this->respond($widgets);
  }

  /**
   * @Route("/api/v1/widgets/{hashId}", name="readWidget", methods={"GET"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function readWidget($hashId)
  {
    $widget = $this->getDoctrine()
      ->getRepository(Widget::class)
      ->findByHashId($hashId);

    return $this->respond($widget);
  }

  /**
   * @Route("/api/v1/widgets/{hashId}", name="updateWidget", methods={"PATCH"})
   * @Security("is_granted('ROLE_ADMIN')")
   */
  public function updateWidget($hashId, Request $request)
  {
    $widget = $this->getDoctrine()
      ->getRepository(Widget::class)
      ->findByHashId($hashId);

    if (!$widget)
      throw $this->createNotFoundException(
        'No widget found for id '. $hashId
      );

    $form = $this->createForm(WidgetAPIType::class, $widget);
    $data = json_decode($request->getContent(), true);

    if (isset($data['attributes']))
      $data['attributes'] = json_encode($data['attributes']);

    $form->submit($data);

    //save widget updates to database if valid
    if ($form->isSubmitted() && $form->isValid())
    {
      $widget = $form->getData();
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();

      return $this->respond($widget);
    }

    return $this->respondWithErrors([
      'Invalid Data'
    ]);
  }

  /**
   * @Route("/api/v1/widgets", name="createWidget", methods={"POST"})
   * @Security("is_granted('ROLE_ADMIN')")
   * Pass {type, sortorder, attributes}
   */
  public function createWidget(Request $request)
  {
    $widget = new Widget();
    $form = $this->createForm(WidgetAPIType::class, $widget);
    $data = json_decode($request->getContent(), true);

    if (isset($data['attributes']))
      $data['attributes'] = json_encode($data['attributes']);

    $form->submit($data);

    //save new widget to database if valid
    if ($form->isSubmitted() && $form->isValid())
    {
      $widget = $form->getData();
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($widget);
      $entityManager->flush();

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
  public function deleteWidget($hashId)
  {
    $widget = $this->getDoctrine()
      ->getRepository(Widget::class)
      ->findByHashId($hashId);

    if (!$widget)
      throw $this->createNotFoundException(
        'No widget found for id '. $hashId
      );

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($widget);
    $entityManager->flush();

    return $this->respondWithNull();
  }
}
