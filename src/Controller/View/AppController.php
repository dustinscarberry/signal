<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Subscription;
use App\Form\SubscriptionType;
use App\Form\SubscriptionManagementType;
use App\Service\Factory\SubscriptionFactory;
use App\Service\Factory\WidgetFactory;
use App\Service\Factory\IncidentFactory;
use App\Service\Factory\MaintenanceFactory;
use App\Model\SubscriptionManagement;

class AppController extends AbstractController
{
  #[Route('/', name: 'home')]
  public function home(
    Request $req,
    SubscriptionFactory $subscriptionFactory,
    WidgetFactory $widgetFactory
  ) {
    $widgets = $widgetFactory->getWidgets();

    //create subscription object
    $subscription = new Subscription();

    //create form object for subscription
    $form = $this->createForm(SubscriptionType::class, $subscription);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $subscriptionFactory->createSubscription($subscription);

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

  #[Route('/subscription/{subscriptionID}', name: 'manageSubscription')]
  public function manageSubscription($subscriptionID, Request $req, SubscriptionManagement $subscriptionManagement)
  {
    //initialize subscription management fields
    $subscriptionManagement->initialize($subscriptionID);

    //create form object for subscription management
    $form = $this->createForm(SubscriptionManagementType::class, $subscriptionManagement);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $subscriptionManagement->update($subscriptionID);
      $this->addFlash('success', 'Subscription updated');
    }

    return $this->render('app/subscription.html.twig', [
      'subscriptionManagementForm' => $form->createView()
    ]);
  }

  #[Route('/incident/{incidentId}', name: 'viewIncident')]
  public function viewIncident($incidentId, IncidentFactory $incidentFactory)
  {
    $incident = $incidentFactory->getIncident($incidentId);

    return $this->render('app/viewincident.html.twig', [
      'incident' => $incident
    ]);
  }

  #[Route('/pastincidents', name: 'viewPastIncidents')]
  public function viewPastIncidents(IncidentFactory $incidentFactory)
  {
    $incidents = $incidentFactory->getPastIncidents(true);

    return $this->render('app/viewpastincidents.html.twig', [
      'incidents' => $incidents
    ]);
  }

  #[Route('/maintenance/{maintenanceId}', name: 'viewMaintenance')]
  public function viewMaintenance($maintenanceId, MaintenanceFactory $maintenanceFactory)
  {
    $maintenance = $maintenanceFactory->getMaintenance($maintenanceId);

    return $this->render('app/viewmaintenance.html.twig', [
      'maintenance' => $maintenance
    ]);
  }

  #[Route('/pastmaintenance', name: 'viewPastMaintenance')]
  public function viewPastMaintenance(MaintenanceFactory $maintenanceFactory)
  {
    $maintenances = $maintenanceFactory->getPastMaintenances(true);

    return $this->render('app/viewpastmaintenance.html.twig', [
      'maintenances' => $maintenances
    ]);
  }

  #[Route('/scheduledmaintenance', name: 'viewScheduledMaintenance')]
  public function viewScheduledMaintenance(MaintenanceFactory $maintenanceFactory)
  {
    $maintenances = $maintenanceFactory->getScheduledMaintenances();

    return $this->render('app/viewscheduledmaintenance.html.twig', [
      'maintenances' => $maintenances
    ]);
  }
}
