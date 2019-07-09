<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Widget;
use App\Entity\Subscription;
use App\Form\SubscriptionType;
use App\Form\SubscriptionManagementType;
use App\Service\Mail\Mailer\SubscriptionCreatedMailer;

use App\Model\SubscriptionManagement;

class AppController extends AbstractController
{
  /**
   * @Route("/")
   */
  public function home(Request $request, SubscriptionCreatedMailer $subscriptionMailer)
  {
    $widgets = $this->getDoctrine()
      ->getRepository(Widget::class)
      ->findAllSorted();

    //create subscription object
    $subscription = new Subscription();

    //create form object for subscription
    $form = $this->createForm(SubscriptionType::class, $subscription);

    //handle form request if posted
    $form->handleRequest($request);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $subscription = $form->getData();

      $em = $this->getDoctrine()->getManager();
      $em->persist($subscription);
      $em->flush();

      //send subscription created email to user
      $subscriptionMailer->send($subscription);

      $this->addFlash('success', 'Congrats! Your subscribed');

      return $this->redirectToRoute(
        'manageSubscription',
        ['subscriptionID' => $subscription->getHashId()]
      );
    }

    return $this->render('app/home.html.twig', [
      'widgets' => $widgets,
      'subscriptionForm' => $form->createView()
    ]);
  }

  /**
   * @Route("/subscription/{subscriptionID}", name="manageSubscription")
   */
  public function manageSubscription($subscriptionID, Request $req, SubscriptionManagement $subscriptionManagement)
  {
    //initialize subscription management fields
    $subscriptionManagement->initialize($subscriptionID);

    //create form object for subscription management
    $form = $this->createForm(SubscriptionManagementType::class, $subscriptionManagement);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $subscriptionManagement->update($subscriptionID);

      $this->addFlash('success', 'Subscription updated');
    }

    return $this->render('app/subscription.html.twig', [
      'subscriptionManagementForm' => $form->createView()
    ]);
  }
}
