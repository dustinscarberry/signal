<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\WidgetOrderAPIType;
use App\Model\WidgetOrder;

class WidgetOrderApiController extends ApiController
{
  /**
   * @Route("/api/v1/widgetsorder", name="updateWidgetsOrder", methods={"PATCH"})
   * @Security("is_granted('ROLE_APIUSER') or is_granted('ROLE_ADMIN')")
   */
  public function updateWidgetsOrder(Request $request, WidgetOrder $widgetOrder)
  {
    $form = $this->createForm(WidgetOrderAPIType::class, $widgetOrder);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);

    //save widget updates to database if valid
    if ($form->isSubmitted() && $form->isValid())
    {
      $widgetOrder = $form->getData();
      $widgetOrder->save();

      return $this->respond($widgetOrder);
    }

    return $this->respondWithErrors([
      'Invalid Data'
    ]);
  }
}
