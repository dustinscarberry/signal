<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Subscription;

class SubscriptionController extends AbstractController
{
  /**
   * @Route("/dashboard/subscriptions", name="viewSubscriptions")
   */
  public function viewall()
  {
    $subscriptions = $this->getDoctrine()
      ->getRepository(Subscription::class)
      ->findAll();

    return $this->render('dashboard/subscription/viewall.html.twig', [
      'subscriptions' => $subscriptions
    ]);
  }
}
