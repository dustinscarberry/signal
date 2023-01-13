<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\CustomMetric;
use App\Form\CustomMetricType;
use App\Service\Factory\CustomMetricFactory;

class CustomMetricController extends AbstractController
{
  #[Route('/dashboard/custommetrics', name: 'viewCustomMetrics')]
  public function customMetrics(CustomMetricFactory $customMetricFactory)
  {
    $metrics = $customMetricFactory->getCustomMetrics();

    return $this->render('dashboard/custommetric/viewall.html.twig', [
      'metrics' => $metrics
    ]);
  }

  #[Route('/dashboard/custommetrics/add', name: 'addCustomMetric')]
  public function add(Request $req, CustomMetricFactory $customMetricFactory)
  {
    //create metric object
    $metric = new CustomMetric();

    //create form object for metric
    $form = $this->createForm(CustomMetricType::class, $metric);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid()) {
      $customMetricFactory->createCustomMetric($metric);

      $this->addFlash('success', 'Metric created');
      return $this->redirectToRoute('viewCustomMetrics');
    }

    //render custom metric add page
    return $this->render('dashboard/custommetric/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  #[Route('/dashboard/custommetrics/{hashId}', name: 'editCustomMetric')]
  public function edit($hashId, Request $req, CustomMetricFactory $customMetricFactory)
  {
    //get metric from database
    $metric = $customMetricFactory->getCustomMetric($hashId);

    //create form object for metric
    $form = $this->createForm(CustomMetricType::class, $metric);

    //handle form request if posted
    $form->handleRequest($req);

    //save form data to database if posted and validated
    if ($form->isSubmitted() && $form->isValid())
    {
      $customMetricFactory->updateCustomMetric();

      $this->addFlash('success', 'Metric updated');
      return $this->redirectToRoute('viewCustomMetrics');
    }

    //render incident status edit page
    return $this->render('dashboard/custommetric/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
